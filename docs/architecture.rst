.. index::
   single: Architecture

############
Architecture
############

This page explains the moving parts of the bundle and the responsibilities of
each layer. Understanding this structure makes the rest of the documentation —
and the codebase itself — easy to navigate, because every resource follows the
exact same pattern.

.. contents:: Table of contents
   :depth: 2
   :local:

***************
The five layers
***************

For every ESPN resource there are (up to) five collaborating classes, each
with a single, narrow responsibility.

DTO (from the client)
=====================

The client returns a DTO — a typed, read-only view of one ESPN resource. The
bundle never persists a DTO; it is only the raw material. DTOs and the
``{name}Reference`` convention are documented in the client's docs.

Entity
======

A Doctrine entity that represents the persisted resource. Entities use
ESPN's id (stored as ``espnId``), bigint identity primary keys, nullable
columns, enums for fixed vocabularies, and a ``SyncTimestampsTrait`` that
records ``createdAt`` / ``lastSyncedAt``. Relationships between resources are
modeled with real Doctrine associations. See :doc:`entities`.

Repository
==========

A Doctrine repository with a ``findByDtoOrCreateEntity()`` style method that
locates an existing row (by its natural key) or returns a fresh entity, so
imports are idempotent — re-importing updates the existing row rather than
duplicating it.

Converter
=========

A converter maps a DTO onto an entity. It is deliberately limited: it sets
**only scalar properties and reference strings**. It never connects one entity
to another. This keeps converters pure and side-effect free.

Importer
========

An importer orchestrates a single resource import: it resolves the resource's
identifiers from a reference URL, fetches the DTO through the client, calls the
converter, and then **connects the entity to its related entities** (the team
its athlete belongs to, the season its event sits in, and so on). The importer
is the only place entity-to-entity connections happen.

Message & handler
=================

A Messenger message carries the reference (and any parent entity ids) needed
to import one resource. Its handler runs the importer, persists the resulting
entity, flushes, and then **dispatches messages for the resource's children**.
This is what turns a single import into a cascade.

***************
Why converters and importers are split
***************

The strict division — converters set scalars, importers connect entities — is
the single most important convention in the bundle. It exists because:

* **Persistence stays predictable.** A handler persists exactly the one entity
  its importer returns. Related entities are fetched from the repository within
  the same Doctrine unit of work, so when the handler flushes, their changes
  are written too — without the converter silently persisting objects it
  doesn't own.
* **Converters are trivially testable.** With no database or relationship
  logic, a converter is a pure DTO-to-entity mapping.
* **Connections live in one place.** When you need to understand how an
  athlete gets linked to its team, there is exactly one method to read: the
  importer's ``connect*`` method.

***************
The cascade
***************

Handlers dispatch child messages after they persist. The shape of the cascade
mirrors the ESPN resource tree:

.. code-block:: text

    ImportEspnSeasonMessage
      └─ SeasonType
           ├─ SeasonGroup (recurses into child groups)
           │    └─ Team
           │         ├─ Venue
           │         ├─ Franchise
           │         ├─ Records
           │         ├─ TeamInjuries ─→ Injury
           │         ├─ Athlete ─→ Contract, Injury
           │         └─ Coach
           └─ Week
                └─ Event
                     └─ Competition
                          ├─ CompetitionStatus
                          ├─ Competitor ─→ Score
                          └─ Official

    ImportEspnPositionsMessage
      └─ Position (resolves its parent inline)

Which branches actually run is decided by the import-control flags — see
:doc:`import_control`. The full message catalog and the parent ids each
message carries are documented in :doc:`import_chain`.

***************
Configuration surface
***************

The bundle has no DI extension. Everything is wired through the service YAML
files it ships (repositories, converters, importers, message handlers) plus
the Messenger configuration you copy from the ``.dist`` files. "Configuring"
the bundle means:

#. Setting up Messenger transports, routing, and retries (:doc:`messenger`).
#. Choosing what to import via the import-control flags
   (:doc:`import_control`).

**********
Read next
**********

* :doc:`entities` — the data model
* :doc:`import_control` — deciding what gets imported
