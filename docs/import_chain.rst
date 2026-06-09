.. index::
   single: Import chain

############
Import chain
############

This page is the reference for every message in the import pipeline: what it
imports, what it carries, and which child messages it dispatches. All messages
live in ``HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync``.

.. contents:: Table of contents
   :depth: 2
   :local:

***************
Anatomy of a message
***************

Almost every message carries the same shape:

* a ``reference`` — the ESPN ``$ref`` URL of the resource to import;
* zero or more **parent entity ids** — the database ids of already-imported
  parents the new entity must be connected to;
* an optional ``importEntities`` array — the import-control flags
  (:doc:`import_control`).

The parent ids matter: by the time a child message runs, its parent has
already been persisted, so the child carries the parent's *database id* (not
its ESPN ref) to connect to it cheaply.

A handful of messages differ:

* ``ImportEspnPositionsMessage`` carries no reference — it is a root trigger
  that lists all positions and dispatches one ``ImportEspnPositionMessage``
  each.
* ``ImportEspnTeamInjuriesMessage`` carries a ``teamId`` rather than a
  reference — it refreshes a team's injuries.

***************
Root triggers
***************

These are the two entry points for a full import. Dispatch them yourself.

==================================== ============================================ ===============================================
Message                              Constructor                                  Dispatches
==================================== ============================================ ===============================================
``ImportEspnSeasonMessage``          ``(string $reference, ?array $importEntities)`` SeasonType, Team (season level)
``ImportEspnPositionsMessage``       ``(?array $importEntities)``                 Position (one per league position)
==================================== ============================================ ===============================================

***************
Season tree
***************

==================================== ===================================================================== ===============================================
Message                              Constructor                                                           Dispatches
==================================== ===================================================================== ===============================================
``ImportEspnSeasonTypeMessage``      ``(string $reference, int $seasonId, bool $isCurrent, ?array $ie)``    SeasonGroup, Week
``ImportEspnSeasonGroupMessage``     ``(string $reference, int $seasonId, ?int $parentGroupId, ?array $ie)`` child SeasonGroup (recursion), Team (group level), Standing
``ImportEspnWeekMessage``            ``(string $reference, int $seasonTypeId, ?array $ie)``                Event
``ImportEspnStandingMessage``        ``(string $reference, int $seasonGroupId, int $seasonId, ?array $ie)`` Record (one per standing entry ref)
==================================== ===================================================================== ===============================================

.. note::

    ``ImportEspnSeasonGroupMessage`` is recursive: a group dispatches messages
    for its child groups, walking the hierarchy until it reaches leaf groups.
    The ``parentGroupId`` carries the already-persisted parent so each group is
    linked to its parent.

***************
Team tree
***************

==================================== ===================================================================== ===============================================
Message                              Constructor                                                           Dispatches
==================================== ===================================================================== ===============================================
``ImportEspnTeamMessage``            ``(string $reference, int $seasonId, ?array $ie)``                    Venue, Franchise, Record, Athlete, Coach, TeamInjuries
``ImportEspnVenueMessage``           ``(string $reference, ?array $ie)``                                   — (leaf)
``ImportEspnFranchiseMessage``       ``(string $reference, ?array $ie)``                                   — (leaf)
``ImportEspnRecordMessage``          ``(string $reference, ?array $ie)``                                   — (leaf)
``ImportEspnCoachMessage``           ``(string $reference, int $seasonId, ?array $ie)``                    — (leaf)
``ImportEspnTeamInjuriesMessage``    ``(int $teamId, ?array $ie)``                                         Injury (after deleting the team's current injuries)
==================================== ===================================================================== ===============================================

***************
Athlete tree
***************

==================================== ===================================================================== ===============================================
Message                              Constructor                                                           Dispatches
==================================== ===================================================================== ===============================================
``ImportEspnAthleteMessage``         ``(string $reference, int $seasonId, ?array $ie)``                    Contract, Injury
``ImportEspnContractMessage``        ``(string $reference, int $athleteId, ?array $ie)``                   — (leaf)
``ImportEspnInjuryMessage``          ``(string $reference, ?array $ie)``                                   — (leaf)
==================================== ===================================================================== ===============================================

.. note::

    An athlete's notes are imported inline by the athlete importer (not as a
    separate message). The same is true for a team's notes. Injuries are
    connected to **all** season instances of the athlete sharing the same
    ``espnId``, because an injury is season-independent.

***************
Event tree
***************

==================================== ===================================================================== ===============================================
Message                              Constructor                                                           Dispatches
==================================== ===================================================================== ===============================================
``ImportEspnEventMessage``           ``(string $reference, int $seasonId, int $seasonTypeId, int $weekId, ?array $ie)`` Competition
``ImportEspnCompetitionMessage``     ``(string $reference, int $eventId, ?array $ie)``                     CompetitionStatus, Competitor, Official
``ImportEspnCompetitionStatusMessage`` ``(string $reference, ?array $ie)``                                 — (leaf)
``ImportEspnCompetitorMessage``      ``(string $reference, int $competitionId, ?array $ie)``               Score
``ImportEspnScoreMessage``           ``(string $reference, int $competitorId, ?array $ie)``                — (leaf)
``ImportEspnOfficialMessage``        ``(string $reference, int $competitionId, ?array $ie)``               — (leaf)
==================================== ===================================================================== ===============================================

***************
Positions tree
***************

==================================== ============================================ ===============================================
Message                              Constructor                                  Dispatches
==================================== ============================================ ===============================================
``ImportEspnPositionMessage``        ``(string $reference, ?array $ie)``          — (resolves its parent position inline)
==================================== ============================================ ===============================================

***************
Dispatching mid-chain
***************

Because every message connects to its parents through ids it carries, you can
enter the chain at any point — as long as the parents it references already
exist in your database. This is the basis for targeted refreshes:

.. code-block:: php

    use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnTeamMessage;
    use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;

    // Re-import a single team (the season must already be imported)
    $teamRef = EspnApiClientInterface::BASE_URI_SPORTS_CORE . 'seasons/2025/teams/12';

    $bus->dispatch(new ImportEspnTeamMessage($teamRef, $seasonEntityId));

If a referenced parent is **not** present, the importer raises an unrecoverable
error rather than guessing — see :doc:`error_handling`.

**********
Read next
**********

* :doc:`messenger` — transports, routing, and retries
* :doc:`error_handling` — recoverable vs. unrecoverable failures
