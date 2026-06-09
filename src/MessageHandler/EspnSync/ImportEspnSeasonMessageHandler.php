<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnSeasonMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnSeasonTypeMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnTeamMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ImportEspnSeasonMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnApiClientInterface $espnApiClient,
        private readonly EspnSeasonImporter $espnSeasonImporter,
        private readonly MessageBusInterface $messageBus,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnSeasonMessage $message): void
    {
        try {
            $importEntities = $message->importEntities ?? EspnImportService::getSeasonImportEntities();

            $espnSeason = $this->espnSeasonImporter->buildEntityFromReference($message->reference);

            $this->entityManager->persist($espnSeason);
            $this->entityManager->flush();

            $this->dispatchSubsequentMessages($espnSeason, $importEntities);
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnSeasonMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (\Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnSeasonMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw $e;
        }
    }

    private function dispatchSubsequentMessages(EspnSeason $espnSeason, array $importEntities): void
    {
        $skipCurrent = false;

        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_SEASON_TYPE)
            && null !== $espnSeason->getTypeReference()
        ) {
            $this->messageBus->dispatch(new ImportEspnSeasonTypeMessage(
                reference: $espnSeason->getTypeReference(),
                seasonId: $espnSeason->getId(),
                isCurrent: true,
                importEntities: $importEntities,
            ));
            $skipCurrent = true;
        }

        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_SEASON_TYPES)
            && null !== $espnSeason->getTypesReference()
        ) {
            $seasonTypeReferences = $this->espnApiClient->seasons()->seasonTypes()->listRefs(
                $espnSeason->getEspnYear()
            );

            foreach ($seasonTypeReferences as $seasonTypeReference) {
                $isCurrent = $seasonTypeReference === $espnSeason->getTypeReference();
                if ($isCurrent && $skipCurrent) {
                    continue;
                }

                $this->messageBus->dispatch(new ImportEspnSeasonTypeMessage(
                    reference: $seasonTypeReference,
                    seasonId: $espnSeason->getId(),
                    isCurrent: $isCurrent,
                    importEntities: $importEntities,
                ));
            }
        }

        if ($this->shouldImportTeamsFromSeason($importEntities)) {
            $teamReferences = $this->espnApiClient->seasons()->teams()->listRefs(
                $espnSeason->getEspnYear()
            );

            foreach ($teamReferences as $teamReference) {
                $this->messageBus->dispatch(new ImportEspnTeamMessage(
                    reference: $teamReference,
                    seasonId: $espnSeason->getId(),
                    importEntities: $importEntities,
                ));
            }
        }
    }
}
