.. index::
   single: Extending

#########
Extending
#########

The bundle's strict layering makes it predictable to extend. Whether you are
adding a brand-new ESPN resource or customizing how an existing one is
imported, you follow the same five-layer pattern the bundle uses everywhere.

.. contents:: Table of contents
   :depth: 2
   :local:

***************
Adding a new resource
***************

Suppose ESPN exposes a resource the bundle does not yet import. To add it, you
create up to six classes — the same set every existing resource has.

1. Entity
=========

Create a Doctrine entity following the conventions in :doc:`entities`: an
``espnId`` column, a bigint identity key, nullable columns, the
``SyncTimestampsTrait``, and real associations to related entities. Store links
to other resources as ``{name}Reference`` columns.

.. code-block:: php

    #[ORM\Entity(repositoryClass: EspnFooRepository::class)]
    #[ORM\Table(name: 'easb_espn_foo')]
    #[ORM\HasLifecycleCallbacks]
    class EspnFoo
    {
        use SyncTimestampsTrait;

        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: 'IDENTITY')]
        #[ORM\Column(type: 'bigint')]
        private ?int $id = null;

        #[ORM\Column(nullable: true)]
        private ?string $espnId = null;

        // ... scalar columns, reference columns, associations ...
    }

2. Repository
=============

Add a ``findByDtoOrCreateEntity()`` style method that finds the existing row
by its natural key or returns a new entity, keeping imports idempotent.

.. code-block:: php

    public function findByDtoOrCreateEntity(EspnFooDto $dto): EspnFoo
    {
        return $this->findOneBy(['espnId' => $dto->getId()]) ?? new EspnFoo();
    }

3. Converter
============

Map the DTO's **scalars and reference strings** onto the entity — nothing
else. No entity-to-entity connections here.

.. code-block:: php

    public function toEntity(EspnFooDto $dto): EspnFoo
    {
        $entity = $this->repository->findByDtoOrCreateEntity($dto);

        $entity->setEspnId($dto->getId());
        $entity->setDisplayName($dto->getDisplayName());
        $entity->setBarReference($dto->getBarReference());

        return $entity;
    }

4. Importer
===========

Resolve the resource's identifiers from its reference, fetch the DTO through
the client, call the converter, then **connect related entities**. This is the
only place connections happen.

.. code-block:: php

    public function buildEntityFromReference(string $reference): EspnFoo
    {
        $params = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_FOO
        );

        if (null === $params->fooId) {
            throw new UnrecoverableImportException(
                sprintf('Could not resolve fooId from: %s', $reference)
            );
        }

        $dto = $this->espnApiClient->foos()->get($params->fooId);

        if (!$dto) {
            // primary resource missing from API -> retryable
            throw new ImportException(sprintf('Foo %d not found', $params->fooId));
        }

        $entity = $this->converter->toEntity($dto);

        // connect a parent that must already exist -> unrecoverable if missing
        $this->connectBar($entity, $dto->getBarReference());

        return $entity;
    }

Use ``UnrecoverableImportException`` for URL-resolution and
parent-not-found-in-DB cases, and a plain ``ImportException`` for the primary
resource missing from the API. See :doc:`error_handling`.

5. Message
==========

A readonly message carrying the reference, any parent entity ids, and the
import-control array.

.. code-block:: php

    class ImportEspnFooMessage
    {
        public function __construct(
            public readonly string $reference,
            public readonly ?array $importEntities = null,
        ) {}
    }

6. Handler
==========

Persist the entity, flush, dispatch children, and apply the two-arm catch from
:doc:`error_handling`.

.. code-block:: php

    #[AsMessageHandler]
    class ImportEspnFooMessageHandler
    {
        use ImportEntitiesHelperTrait;

        public function __invoke(ImportEspnFooMessage $message): void
        {
            try {
                $foo = $this->importer->buildEntityFromReference($message->reference);
                $this->entityManager->persist($foo);
                $this->entityManager->flush();
                // dispatch children guarded by $this->shouldImport(...)
            } catch (UnrecoverableImportException $e) {
                $this->importLogger->critical(/* ... */);
                throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
            } catch (\Throwable $e) {
                $this->importLogger->warning(/* ... */);
                throw $e;
            }
        }
    }

Finally, register the new converter, importer, repository, and handler in the
bundle's service YAML, route the message in ``messenger.yaml``, and run
``make:migration``.

***************
Connecting from the owning side
***************

When you connect a bidirectional association in an importer, set it from the
**owning** side so Doctrine writes the foreign key, and let the entity's setter
keep both sides consistent. The bundle's ``OneToOne`` setters use a
recursion-guarded pattern:

.. code-block:: php

    public function setTeam(?EspnTeam $team): static
    {
        $this->team = $team;
        if (null !== $team && $team->getFranchise() !== $this) {
            $team->setFranchise($this);   // keep the owning side in sync
        }
        return $this;
    }

The guard (``!== $this``) prevents infinite recursion between the two setters.

***************
Adding a flag
***************

If your new resource should be optional in the cascade, add a constant to
``EspnImportService`` and gate its dispatch with ``shouldImport()``:

.. code-block:: php

    // in EspnImportService
    public const IMPORT_ENTITY_FOO = 'import_entity_foo';

    // in the parent handler, before dispatching the foo message
    if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_FOO)) {
        $this->messageBus->dispatch(new ImportEspnFooMessage($fooRef, $importEntities));
    }

Add it to ``getSeasonImportEntities()`` (or the relevant default set) if it
should run by default. If the resource is always imported alongside its parent
(like notes), skip the flag and import it unconditionally in the importer.

***************
Numeric-as-string fields
***************

If a new ESPN field arrives as a JSON number but you store it as a string, add
the type-enforcement-disabling context to the **DTO** property (in the client
package), exactly as the existing DTOs do. The entity column can then be a
plain string or a ``decimal``.

***************
Reserved SQL words
***************

If a column name collides with a SQL reserved word (``order``, ``user`` …),
map it to a safe column name explicitly:

.. code-block:: php

    #[ORM\Column(name: 'display_order', nullable: true)]
    private ?int $displayOrder = null;

**********
Read next
**********

* :doc:`contribute` — contributing your extension upstream
