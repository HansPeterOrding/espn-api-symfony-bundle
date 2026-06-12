<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnWeek;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnWeekImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnEventMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnWeekMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
class ImportEspnWeekMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnApiClientInterface   $espnApiClient,
        private readonly EspnWeekImporter         $espnWeekImporter,
        private readonly EspnSeasonTypeRepository $espnSeasonTypeRepository,
        private readonly MessageBusInterface      $messageBus,
        private readonly EntityManagerInterface   $entityManager,
        private readonly LoggerInterface          $importLogger,
    )
    {
    }

    public function __invoke(ImportEspnWeekMessage $message): void
    {
        try {
            $importEntities = $message->importEntities ?? EspnImportService::getSeasonImportEntities();

            $espnSeasonType = $this->espnSeasonTypeRepository->find($message->seasonTypeId);
            if (null === $espnSeasonType) {
                throw new UnrecoverableMessageHandlingException(sprintf(
                    'SeasonType with id %d not found. Import the season type first.',
                    $message->seasonTypeId
                ));
            }

            $espnWeek = $this->espnWeekImporter->buildEntityFromReference(
                $message->reference,
                $espnSeasonType
            );

            $this->entityManager->persist($espnWeek);
            $this->entityManager->flush();

            $this->dispatchSubsequentMessages($espnWeek, $importEntities);
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnWeekMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'seasonTypeId' => $message->seasonTypeId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnWeekMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'seasonTypeId' => $message->seasonTypeId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw $e;
        }
    }

    private function dispatchSubsequentMessages(EspnWeek $espnWeek, array $importEntities): void
    {
        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_EVENTS)
            && null !== $espnWeek->getEventsReference()
        ) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $espnWeek->getEventsReference(),
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_WEEK_EVENTS
            );

            $eventReferences = $this->espnApiClient->seasons()->seasonTypes()->weeks()->events()->listRefsForWeek(
                $urlParams->year,
                $urlParams->typeId,
                $urlParams->weekNumber
            );

            foreach ($eventReferences as $eventReference) {
                $this->messageBus->dispatch(new ImportEspnEventMessage(
                    reference: $eventReference,
                    seasonId: $espnWeek->getSeasonType()->getSeason()->getId(),
                    seasonTypeId: $espnWeek->getSeasonType()->getId(),
                    weekId: $espnWeek->getId(),
                    importEntities: $importEntities,
                ));
            }
        }
    }
}
