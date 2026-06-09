<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnCompetitorImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnCompetitorMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnScoreMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ImportEspnCompetitorMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnCompetitorImporter $espnCompetitorImporter,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnCompetitorMessage $message): void
    {
        try {
            $importEntities = $message->importEntities ?? EspnImportService::getSeasonImportEntities();

            $espnCompetitor = $this->espnCompetitorImporter->buildEntityFromReference(
                $message->reference,
                $message->competitionId,
            );

            $this->entityManager->persist($espnCompetitor);
            $this->entityManager->flush();

            // Dispatch score message
            if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_SCORE)
                && null !== $espnCompetitor->getScoreReference()
            ) {
                $this->messageBus->dispatch(new ImportEspnScoreMessage(
                    reference: $espnCompetitor->getScoreReference(),
                    competitorId: $espnCompetitor->getId(),
                    importEntities: $importEntities,
                ));
            }
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnCompetitorMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (\Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnCompetitorMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw $e;
        }
    }
}
