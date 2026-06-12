<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnSeasonGroupMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnSeasonTypeMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnWeekMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
class ImportEspnSeasonTypeMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnApiClientInterface $espnApiClient,
        private readonly EspnSeasonTypeImporter $espnSeasonTypeImporter,
        private readonly EspnSeasonRepository   $espnSeasonRepository,
        private readonly MessageBusInterface    $messageBus,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $importLogger,
    )
    {
    }

    public function __invoke(ImportEspnSeasonTypeMessage $message): void
    {
        try {
            $importEntities = $message->importEntities ?? EspnImportService::getSeasonImportEntities();

            $espnSeason = $this->espnSeasonRepository->find($message->seasonId);
            if (null === $espnSeason) {
                throw new UnrecoverableMessageHandlingException(sprintf(
                    'Season with id %d not found. Import the season first.',
                    $message->seasonId
                ));
            }

            $espnSeasonType = $this->espnSeasonTypeImporter->buildEntityFromReference(
                $message->reference,
                $espnSeason,
                $message->isCurrent
            );

            $this->entityManager->persist($espnSeasonType);
            $this->entityManager->flush();

            $this->dispatchSubsequentMessages($espnSeasonType, $importEntities);
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnSeasonTypeMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'seasonId' => $message->seasonId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnSeasonTypeMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'seasonId' => $message->seasonId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw $e;
        }
    }

    private function dispatchSubsequentMessages(EspnSeasonType $espnSeasonType, array $importEntities): void
    {
        if ($this->shouldImportSeasonGroups($importEntities, $espnSeasonType->getIsCurrent() ?? false)
            && null !== $espnSeasonType->getGroupsReference()
        ) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $espnSeasonType->getGroupsReference(),
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_GROUPS
            );

            $seasonGroupReferences = $this->espnApiClient->seasons()->seasonTypes()->seasonGroups()->listRefs(
                $urlParams->year,
                $urlParams->typeId
            );

            foreach ($seasonGroupReferences as $seasonGroupReference) {
                $this->messageBus->dispatch(new ImportEspnSeasonGroupMessage(
                    reference: $seasonGroupReference,
                    seasonId: $espnSeasonType->getSeason()->getId(),
                    importEntities: $importEntities,
                ));
            }
        }

        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_WEEKS)
            && null !== $espnSeasonType->getWeeksReference()
        ) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $espnSeasonType->getWeeksReference(),
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_WEEKS
            );

            $weekConfig = $importEntities[EspnImportService::IMPORT_ENTITY_WEEKS];
            $allowedWeeks = is_array($weekConfig) && isset($weekConfig['weeks'])
                ? $weekConfig['weeks']
                : null;

            $weekReferences = $this->espnApiClient->seasons()->seasonTypes()->weeks()->listRefs(
                $urlParams->year,
                $urlParams->typeId
            );

            foreach ($weekReferences as $weekReference) {
                if (null !== $allowedWeeks) {
                    $weekUrlParams = EspnUrlPatternResolver::resolveAll(
                        $weekReference,
                        EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_WEEK
                    );
                    if (!in_array($weekUrlParams->weekNumber, $allowedWeeks, true)) {
                        continue;
                    }
                }

                $this->messageBus->dispatch(new ImportEspnWeekMessage(
                    reference: $weekReference,
                    seasonTypeId: $espnSeasonType->getId(),
                    importEntities: $importEntities,
                ));
            }
        }
    }
}
