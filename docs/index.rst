.. index::
   single: EspnApiSymfonyBundle

#####################
EspnApiSymfonyBundle
#####################

``EspnApiSymfonyBundle`` turns the raw ESPN NFL data exposed by
`espn-api-client`_ into a fully persisted, relational dataset inside your
Symfony application. It provides Doctrine entities for every ESPN resource, an
asynchronous import pipeline built on Symfony Messenger, and a flexible
control layer that lets you decide exactly which parts of the ESPN data tree
get imported.

Where the client answers *"give me this one ESPN resource as an object"*, this
bundle answers *"keep my database in sync with ESPN"*.

.. contents:: Table of contents
   :depth: 2
   :local:

************
What it does
************

* **Entities** — a Doctrine entity for every ESPN resource (seasons, teams,
  athletes, events, competitions, injuries, contracts, and more), with the
  relationships between them modeled explicitly.
* **Importers & converters** — services that fetch a resource through the
  client, map it onto an entity, and connect it to its related entities.
* **Messages & handlers** — one Symfony Messenger message per resource. The
  import is a cascade: importing a season dispatches messages for its weeks
  and teams, importing a team dispatches messages for its athletes, and so on.
* **Import control** — a single nested-array structure decides which branches
  of the cascade run, so you can import everything, or just one team, or just
  live scores during a game.
* **Retry-aware error handling** — transient ESPN failures are retried by
  Messenger; permanent failures (a missing parent, an unresolvable URL) are
  marked unrecoverable and dropped.

************
How it fits together
************

.. code-block:: text

    ESPN API
       │
       ▼
    espn-api-client          ← fetches a resource, returns a DTO
       │
       ▼
    Converter                ← maps DTO scalars onto an entity
       │
       ▼
    Importer                 ← connects the entity to related entities
       │
       ▼
    Message handler          ← persists the entity, dispatches child messages
       │
       ▼
    Doctrine / your database

Every resource follows this same path. Once you understand it for one entity,
you understand it for all of them.

***************
A first import
***************

The most common entry point dispatches two messages — one for the season tree
and one for the (season-independent) positions tree. Each import message takes
the ESPN ``$ref`` URL of the resource to import:

.. code-block:: php

    use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
    use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnSeasonMessage;
    use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnPositionsMessage;
    use Symfony\Component\Messenger\MessageBusInterface;

    public function fullImport(MessageBusInterface $bus): void
    {
        $seasonRef = EspnApiClientInterface::BASE_URI_SPORTS_CORE . 'seasons/2025';

        $bus->dispatch(new ImportEspnSeasonMessage($seasonRef));
        $bus->dispatch(new ImportEspnPositionsMessage());
    }

That is the whole trigger. Everything else happens asynchronously as the
cascade unfolds across your Messenger workers. You are never *required* to
start here, though — see :doc:`import_control` for how to dispatch any single
message in the chain to import just a slice of the data.

**********
Read next
**********

.. toctree::
    :maxdepth: 1

    installation
    architecture
    entities
    import_control
    import_chain
    messenger
    error_handling
    extending
    contribute

.. _espn-api-client: https://github.com/HansPeterOrding/espn-api-client
