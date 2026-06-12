<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnEventImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnCompetitionMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnEventMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
class ImportEspnEventMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnEventImporter      $espnEventImporter,
        private readonly EspnApiClientInterface $espnApiClient,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface    $messageBus,
        private readonly LoggerInterface        $importLogger,
    )
    {
    }

    public function __invoke(ImportEspnEventMessage $message): void
    {
        try {
            $importEntities = $message->importEntities ?? EspnImportService::getSeasonImportEntities();

            $espnEvent = $this->espnEventImporter->buildEntityFromReference(
                $message->reference,
                $message->seasonId,
                $message->seasonTypeId,
                $message->weekId,
            );

            $this->entityManager->persist($espnEvent);
            $this->entityManager->flush();

            if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_COMPETITIONS)) {
                $urlParams = EspnUrlPatternResolver::resolveAll(
                    $message->reference,
                    EspnUrlPatternResolver::URL_PATTERN_EVENT
                );

                $competitionReferences = $this->espnApiClient->events()->competitions()->listRefs(
                    $urlParams->eventId
                );

                foreach ($competitionReferences as $competitionReference) {
                    $this->messageBus->dispatch(new ImportEspnCompetitionMessage(
                        reference: $competitionReference,
                        eventId: $espnEvent->getId(),
                        importEntities: $importEntities,
                    ));
                }
            }
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnEventMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnEventMessageHandler error',
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
