<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnCompetitionImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnCompetitionMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnCompetitionStatusMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnCompetitorMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnOfficialMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ImportEspnCompetitionMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnCompetitionImporter $espnCompetitionImporter,
        private readonly EspnApiClientInterface $espnApiClient,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnCompetitionMessage $message): void
    {
        try {
            $importEntities = $message->importEntities ?? EspnImportService::getSeasonImportEntities();

            $espnCompetition = $this->espnCompetitionImporter->buildEntityFromReference(
                $message->reference,
                $message->eventId,
            );

            $this->entityManager->persist($espnCompetition);
            $this->entityManager->flush();

            $urlParams = EspnUrlPatternResolver::resolveAll(
                $message->reference,
                EspnUrlPatternResolver::URL_PATTERN_EVENT_COMPETITION
            );

            // Dispatch competition status message
            if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_COMPETITION_STATUS)
                && null !== $espnCompetition->getStatusReference()
            ) {
                $this->messageBus->dispatch(new ImportEspnCompetitionStatusMessage(
                    reference: $espnCompetition->getStatusReference(),
                    importEntities: $importEntities,
                ));
            }

            // Dispatch competitor messages
            if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_COMPETITORS)) {
                $competitorReferences = $this->espnApiClient->events()->competitions()->competitors()->listRefs(
                    $urlParams->eventId,
                    $urlParams->competitionId
                );

                foreach ($competitorReferences as $competitorReference) {
                    $this->messageBus->dispatch(new ImportEspnCompetitorMessage(
                        reference: $competitorReference,
                        competitionId: $espnCompetition->getId(),
                        importEntities: $importEntities,
                    ));
                }
            }

            // Dispatch official messages
            if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_OFFICIALS)
                && null !== $espnCompetition->getOfficialsReference()
            ) {
                $officialReferences = $this->espnApiClient->events()->competitions()->officials()->listRefs(
                    $urlParams->eventId,
                    $urlParams->competitionId
                );

                foreach ($officialReferences as $officialReference) {
                    $this->messageBus->dispatch(new ImportEspnOfficialMessage(
                        reference: $officialReference,
                        competitionId: $espnCompetition->getId(),
                        importEntities: $importEntities,
                    ));
                }
            }
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnCompetitionMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (\Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnCompetitionMessageHandler error',
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
