.. index::
   single: Messenger

#########
Messenger
#########

The import pipeline runs on Symfony Messenger. Every resource import is a
message, and the cascade works by handlers dispatching further messages. This
page covers how to configure transports, routing, and the retry strategy that
underpins the bundle's error handling.

.. contents:: Table of contents
   :depth: 2
   :local:

***************
Why asynchronous
***************

A full season import fans out into tens of thousands of messages (every
athlete, every game, every injury). Running them synchronously would be
impractical. By routing imports to an asynchronous transport, you get:

* **Parallelism** — run multiple workers to import faster.
* **Resilience** — a transient ESPN failure retries one message without
  restarting the whole import.
* **Backpressure** — the queue smooths out ESPN rate limits and database
  load.

***************
The .dist files
***************

The bundle ships two distributable Messenger configurations:

* ``messenger.yaml.dist`` — production-oriented: async transports with a retry
  strategy.
* ``messenger.dev.yaml.dist`` — development-oriented: simpler setup for local
  work.

Copy the one you need into ``config/packages/`` and adapt it. They are
starting points, not fixed requirements — you own your Messenger configuration
and can change transports, routing, and retry behavior freely.

.. code-block:: terminal

    $ cp vendor/hanspeterording/espn-api-symfony-bundle/messenger.yaml.dist \
         config/packages/messenger.yaml

A transport per message type
=============================

The shipped configuration defines a **separate transport for every message
type** rather than routing everything onto one shared queue. This is a
deliberate default, chosen for three reasons:

* **Per-message worker scalability.** You can run more workers for the
  expensive, high-volume message types (athletes, events) and fewer for the
  cheap ones, scaling each branch of the import independently.
* **Per-message monitoring.** Queue depth, throughput, and backlog are visible
  per message type, so you can see at a glance which part of the import is
  lagging.
* **Easier problem and performance observation.** When a specific resource
  type misbehaves — ESPN rate-limits athletes, say — the impact is isolated to
  its own queue, making it far easier to spot, diagnose, and reason about than
  if every message shared one transport.

You are free to configure this differently
===========================================

The per-message-type split is only a sensible default, not a constraint. You
are completely free to arrange transports however suits your workload. Common
alternatives include:

* **Grouping by import regularity** — for example a "live" transport for the
  things you refresh constantly during games (competition status, scores), a
  "daily" transport for rosters and injuries, and a "rarely" transport for
  largely static data (venues, franchises, positions).
* **A single shared transport** — if your volume is modest and you would rather
  keep the configuration minimal.
* **Priority tiers** — a high-priority transport for time-sensitive refreshes
  and a bulk transport for everything else.

Route the messages to whichever transports fit your operational model; the
bundle neither knows nor cares how many transports you use or how you group
them.

***************
Routing
***************

Every import message must be routed to an asynchronous transport; otherwise it
is handled synchronously and the cascade blocks the dispatching process. In the
shipped default, each message type has its own transport and is routed to it:

.. code-block:: yaml

    # config/packages/messenger.yaml
    framework:
        messenger:
            transports:
                async_espn_season:
                    dsn: '%env(MESSENGER_TRANSPORT_DSN)%'
                    options:
                        queue_name: espn_season
                    retry_strategy:
                        max_retries: 3
                        delay: 1000
                        multiplier: 2
                async_espn_team:
                    dsn: '%env(MESSENGER_TRANSPORT_DSN)%'
                    options:
                        queue_name: espn_team
                    retry_strategy:
                        max_retries: 3
                        delay: 1000
                        multiplier: 2
                # ... one transport per message type ...

            routing:
                'HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnSeasonMessage': async_espn_season
                'HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnTeamMessage': async_espn_team
                # ... one routing line per message type ...

If you prefer a different arrangement (see "You are free to configure this
differently" above), simply point several messages at the same transport:

.. code-block:: yaml

    routing:
        'HansPeterOrding\...\ImportEspnCompetitionStatusMessage': async_espn_live
        'HansPeterOrding\...\ImportEspnScoreMessage': async_espn_live
        'HansPeterOrding\...\ImportEspnAthleteMessage': async_espn_bulk
        'HansPeterOrding\...\ImportEspnInjuryMessage': async_espn_bulk

***************
The retry strategy
***************

The retry strategy is the production half of the bundle's error handling. The
import handlers are written so that:

* **Transient failures** (ESPN 5xx, network timeouts) bubble up as ordinary
  exceptions, which Messenger catches and **retries** according to your
  ``retry_strategy``.
* **Permanent failures** (a missing parent entity, an unresolvable reference)
  are thrown as ``UnrecoverableMessageHandlingException``, which Messenger
  does **not** retry — the message goes straight to the failure transport.

This division means your ``retry_strategy`` only ever retries failures that a
retry can actually fix. Configure it to taste:

.. code-block:: yaml

    retry_strategy:
        max_retries: 3      # attempts before sending to the failed transport
        delay: 1000         # initial delay in ms
        multiplier: 2       # exponential backoff
        max_delay: 0        # 0 = no cap

See :doc:`error_handling` for the full picture of which failures are treated
which way.

***************
The failure transport
***************

Configure a failure transport so permanently-failed messages are inspectable
rather than lost:

.. code-block:: yaml

    framework:
        messenger:
            failure_transport: failed
            transports:
                failed:
                    dsn: 'doctrine://default?queue_name=failed'

Inspect and replay failed messages with the standard Messenger tooling:

.. code-block:: terminal

    $ php bin/console messenger:failed:show
    $ php bin/console messenger:failed:retry

***************
Running workers
***************

Consume the transports with one or more workers. With the per-message-type
default you can consume several transports from one worker, or dedicate workers
to specific transports:

.. code-block:: terminal

    # consume a single transport
    $ php bin/console messenger:consume async_espn_team -vv

    # consume several transports with one worker (priority is left-to-right)
    $ php bin/console messenger:consume async_espn_live async_espn_team async_espn_bulk -vv

For throughput, run several workers in parallel (via your process manager —
systemd, Supervisor, or your platform's worker abstraction). Because imports
are idempotent (re-importing updates the existing row), parallel workers are
safe.

.. note::

    A subtle concurrency note: resources that create a shared parent on demand
    (for example two position messages that both try to create the same parent
    position) use ``findOneBy`` look-ups that are not race-safe under heavy
    parallelism. For the small, fast-importing trees where this applies the
    risk is low, but if you run many workers and see occasional duplicate
    parents, reduce concurrency for that part of the import or add a unique
    constraint.

***************
Stopping and restarting
***************

A long import can be paused and resumed simply by stopping and restarting the
workers — the queue holds the pending messages. To stop workers cleanly after
they finish their current message:

.. code-block:: terminal

    $ php bin/console messenger:stop-workers

**********
Read next
**********

* :doc:`error_handling` — the recoverable/unrecoverable split in detail
