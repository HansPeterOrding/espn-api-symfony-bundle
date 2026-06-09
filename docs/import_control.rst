.. index::
   single: Import control

##############
Import control
##############

The import cascade is governed by a single nested associative array — the
*import-entities* structure — that is threaded through every message and
checked by every handler before it dispatches a child. This is how you decide
whether an import pulls the entire ESPN data tree or just one carefully chosen
slice of it.

.. contents:: Table of contents
   :depth: 2
   :local:

***************
The mechanism
***************

Every message in the chain carries an optional ``importEntities`` array. When
a handler is about to dispatch a child message, it first asks
``shouldImport()`` whether that branch is enabled. If the flag is missing or
``false``, the branch is skipped and the cascade stops there.

.. code-block:: php

    // Simplified handler logic
    if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_ATHLETES)) {
        // dispatch ImportEspnAthleteMessage for each athlete ref
    }

The rule ``shouldImport()`` applies is simple: a key counts as enabled if it
exists **and** its value is not ``false``. Any truthy value — ``true``, a
string, or a nested array — enables the branch; the nested values then refine
*how* it runs.

If you dispatch a message without an ``importEntities`` array, the handler
falls back to a default set provided by ``EspnImportService`` (see
:ref:`bundle-default-matrix`).

***************
The flag catalog
***************

All flags are constants on ``EspnImportService``. They fall into two groups:
simple on/off switches, and a few structured flags whose value is a string or
nested array.

Simple on/off flags
===================

These accept ``true`` or ``false``:

============================================ =========================================================
Constant                                     Enables
============================================ =========================================================
``IMPORT_ENTITY_SEASON_TYPES``               Dispatch season types under a season
``IMPORT_ENTITY_WEEKS``                      Dispatch weeks under a season type
``IMPORT_ENTITY_EVENTS``                     Dispatch events under a week
``IMPORT_ENTITY_COMPETITIONS``               Dispatch competitions under an event
``IMPORT_ENTITY_COMPETITION_STATUS``         Dispatch the competition status
``IMPORT_ENTITY_COMPETITORS``                Dispatch competitors under a competition
``IMPORT_ENTITY_OFFICIALS``                  Dispatch officials under a competition
``IMPORT_ENTITY_SCORE``                      Dispatch the score under a competitor
``IMPORT_ENTITY_VENUE``                      Dispatch the venue under a team
``IMPORT_ENTITY_FRANCHISE``                  Dispatch the franchise under a team
``IMPORT_ENTITY_RECORDS``                    Dispatch records under a team
``IMPORT_ENTITY_ATHLETES``                   Dispatch athletes under a team
``IMPORT_ENTITY_COACHES``                    Dispatch coaches under a team
``IMPORT_ENTITY_CONTRACT``                   Dispatch the contract under an athlete
``IMPORT_ENTITY_INJURIES``                   Dispatch injuries (athlete- and team-level)
``IMPORT_ENTITY_POSITION``                   Dispatch positions (positions tree)
============================================ =========================================================

Structured flags
================

Three flags carry more than a boolean.

**Season groups** — ``IMPORT_ENTITY_SEASON_GROUPS`` accepts ``true``,
``false``, or the string ``IMPORT_SEASON_GROUPS_FOR_CURRENT_TYPE_ONLY``
(``'current'``). The string value restricts group import to the *current*
season type only, which is the usual choice (you rarely need group structure
for past season types):

.. code-block:: php

    use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;

    // all season types
    EspnImportService::IMPORT_ENTITY_SEASON_GROUPS => true,

    // only the current season type
    EspnImportService::IMPORT_ENTITY_SEASON_GROUPS
        => EspnImportService::IMPORT_SEASON_GROUPS_FOR_CURRENT_TYPE_ONLY,

**Teams** — ``IMPORT_ENTITY_TEAMS`` accepts ``true``, ``false``, or a nested
array that controls *where* teams are imported from. ESPN exposes teams both
directly under a season and nested under season groups; the nested form lets
you pick:

.. code-block:: php

    EspnImportService::IMPORT_ENTITY_TEAMS => [
        // import teams listed directly under the season
        EspnImportService::IMPORT_TEAMS_LEVEL_SEASON => false,

        // import teams listed under season groups
        EspnImportService::IMPORT_TEAMS_LEVEL_GROUP => [
            // leaf groups (divisions) — the actual team buckets
            EspnImportService::IMPORT_TEAMS_GROUP_TYPE_LEAF => true,
            // conference-level groups
            EspnImportService::IMPORT_TEAMS_GROUP_TYPE_CONFERENCE => false,
        ],
    ],

The group level distinguishes **leaf** groups (divisions, which directly
contain teams) from **conference** groups (which contain other groups). The
default imports teams from leaf groups only, which avoids importing the same
team twice.

**Standings** — ``IMPORT_ENTITY_STANDINGS`` accepts ``false``, ``true``, or a
nested array selecting which standing feeds to import:

.. code-block:: php

    EspnImportService::IMPORT_ENTITY_STANDINGS => [
        EspnImportService::IMPORT_STANDINGS_TYPE_OVERALL  => true,
        EspnImportService::IMPORT_STANDINGS_TYPE_PLAYOFF  => true,
        EspnImportService::IMPORT_STANDINGS_TYPE_EXPANDED => true,
        EspnImportService::IMPORT_STANDINGS_TYPE_DIVISION => true,
    ],

Standings default to ``false``: records are normally imported through the team
path, so the standings feed is an optional extra (useful as a post-gameday
refresh).

.. _bundle-default-matrix:

***************
The default matrix
***************

When a message carries no ``importEntities`` array, handlers fall back to
``EspnImportService::getSeasonImportEntities()`` (for the season tree) or
``getPositionsImportEntities()`` (for the positions tree). The full season
default is:

.. code-block:: php

    [
        IMPORT_ENTITY_SEASON_TYPES   => true,
        IMPORT_ENTITY_SEASON_GROUPS  => IMPORT_SEASON_GROUPS_FOR_CURRENT_TYPE_ONLY,
        IMPORT_ENTITY_WEEKS          => true,
        IMPORT_ENTITY_TEAMS          => [
            IMPORT_TEAMS_LEVEL_SEASON => false,
            IMPORT_TEAMS_LEVEL_GROUP  => [
                IMPORT_TEAMS_GROUP_TYPE_LEAF       => true,
                IMPORT_TEAMS_GROUP_TYPE_CONFERENCE => false,
            ],
        ],
        IMPORT_ENTITY_VENUE          => true,
        IMPORT_ENTITY_FRANCHISE      => true,
        IMPORT_ENTITY_RECORDS        => true,
        IMPORT_ENTITY_STANDINGS      => false,
        IMPORT_ENTITY_EVENTS         => true,
        IMPORT_ENTITY_COMPETITIONS   => true,
        IMPORT_ENTITY_COMPETITION_STATUS => true,
        IMPORT_ENTITY_COMPETITORS    => true,
        IMPORT_ENTITY_SCORE          => true,
        IMPORT_ENTITY_OFFICIALS      => true,
        IMPORT_ENTITY_ATHLETES       => true,
        IMPORT_ENTITY_COACHES        => true,
        IMPORT_ENTITY_CONTRACT       => true,
        IMPORT_ENTITY_POSITION       => true,
        IMPORT_ENTITY_INJURIES       => true,
    ]

The positions default is intentionally minimal:

.. code-block:: php

    [
        IMPORT_ENTITY_POSITION => true,
    ]

.. note::

    Notes (for athletes and teams) and the parent of a position are imported
    **unconditionally** inside their importers — they are not gated by a flag.
    There is therefore no ``IMPORT_ENTITY_NOTES`` flag; if a resource is
    imported, its notes come with it, and a position always resolves its
    parent.

***************
Recipes
***************

Import everything for a season
==============================

.. code-block:: php

    use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;

    $seasonRef = EspnApiClientInterface::BASE_URI_SPORTS_CORE . 'seasons/2025';

    $bus->dispatch(new ImportEspnSeasonMessage($seasonRef));
    $bus->dispatch(new ImportEspnPositionsMessage());

Both messages use the defaults above when no array is passed.

Import only teams and rosters, no games
=======================================

Pass a custom array that disables the event branch:

.. code-block:: php

    use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
    use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService as E;

    $seasonRef = EspnApiClientInterface::BASE_URI_SPORTS_CORE . 'seasons/2025';

    $bus->dispatch(new ImportEspnSeasonMessage($seasonRef, [
        E::IMPORT_ENTITY_SEASON_TYPES => true,
        E::IMPORT_ENTITY_SEASON_GROUPS => E::IMPORT_SEASON_GROUPS_FOR_CURRENT_TYPE_ONLY,
        E::IMPORT_ENTITY_TEAMS => [
            E::IMPORT_TEAMS_LEVEL_GROUP => [
                E::IMPORT_TEAMS_GROUP_TYPE_LEAF => true,
            ],
        ],
        E::IMPORT_ENTITY_ATHLETES => true,
        E::IMPORT_ENTITY_CONTRACT => true,
        E::IMPORT_ENTITY_INJURIES => true,
        // events/competitions/etc. simply omitted -> treated as disabled
    ]));

Refresh live scores during a game
=================================

Dispatch further down the chain. You are not obliged to start at the season —
any message in the catalog can be the entry point. To refresh a single
competition's status and scores, dispatch the competition message with only
those branches enabled:

.. code-block:: php

    use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnCompetitionMessage;
    use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService as E;

    $bus->dispatch(new ImportEspnCompetitionMessage(
        reference: $competitionRef,
        eventId: $eventEntityId,
        importEntities: [
            E::IMPORT_ENTITY_COMPETITION_STATUS => true,
            E::IMPORT_ENTITY_COMPETITORS => true,
            E::IMPORT_ENTITY_SCORE => true,
            E::IMPORT_ENTITY_OFFICIALS => false,
        ],
    ));

Refresh one team's injuries
===========================

The team-level injuries message deletes the team's current injuries and
re-imports them — ideal for a frequent in-season refresh:

.. code-block:: php

    use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnTeamInjuriesMessage;

    $bus->dispatch(new ImportEspnTeamInjuriesMessage($teamEntityId));

**********
Read next
**********

* :doc:`import_chain` — the full message catalog and what each carries
* :doc:`messenger` — transports and retries
