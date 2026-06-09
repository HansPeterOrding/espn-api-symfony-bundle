<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnTeamImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnAthleteMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnCoachMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnFranchiseMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnRecordMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnTeamInjuriesMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnTeamMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnVenueMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ImportEspnTeamMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnApiClientInterface $espnApiClient,
        private readonly EspnTeamImporter $espnTeamImporter,
        private readonly EspnSeasonRepository $espnSeasonRepository,
        private readonly MessageBusInterface $messageBus,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnTeamMessage $message): void
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

            $espnTeam = $this->espnTeamImporter->buildEntityFromReference($message->reference);

            $this->entityManager->persist($espnTeam);
            $this->entityManager->flush();

            $this->dispatchSubsequentMessages($espnTeam, $espnSeason->getId(), $importEntities);
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnTeamMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'seasonId' => $message->seasonId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (\Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnTeamMessageHandler error',
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

    private function dispatchSubsequentMessages(EspnTeam $espnTeam, int $seasonId, array $importEntities): void
    {
        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_VENUE)
            && null !== $espnTeam->getVenueReference()
        ) {
            $this->messageBus->dispatch(new ImportEspnVenueMessage(
                reference: $espnTeam->getVenueReference(),
                importEntities: $importEntities,
            ));
        }

        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_FRANCHISE)
            && null !== $espnTeam->getFranchiseReference()
        ) {
            $this->messageBus->dispatch(new ImportEspnFranchiseMessage(
                reference: $espnTeam->getFranchiseReference(),
                importEntities: $importEntities,
            ));
        }

        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_RECORDS)
            && null !== $espnTeam->getRecordReference()
        ) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $espnTeam->getRecordReference(),
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_TEAM_RECORDS
            );

            $recordReferences = $this->espnApiClient->seasons()->teams()->records()->listRefs(
                $urlParams->year,
                $urlParams->typeId,
                $urlParams->teamId
            );

            foreach ($recordReferences as $recordReference) {
                $this->messageBus->dispatch(new ImportEspnRecordMessage(
                    reference: $recordReference,
                    importEntities: $importEntities,
                ));
            }
        }

        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_ATHLETES)
            && null !== $espnTeam->getAthletesReference()
        ) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $espnTeam->getAthletesReference(),
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TEAM_ATHLETES
            );

            $athleteReferences = $this->espnApiClient->seasons()->athletes()->listRefsForTeam(
                $urlParams->year,
                $urlParams->teamId
            );

            foreach ($athleteReferences as $athleteReference) {
                $this->messageBus->dispatch(new ImportEspnAthleteMessage(
                    reference: $athleteReference,
                    seasonId: $seasonId,
                    importEntities: $importEntities,
                ));
            }
        }

        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_COACHES)
            && null !== $espnTeam->getCoachesReference()
        ) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $espnTeam->getCoachesReference(),
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TEAM_COACHES
            );

            $coachReferences = $this->espnApiClient->seasons()->coaches()->listRefsForTeam(
                $urlParams->year,
                $urlParams->teamId
            );

            foreach ($coachReferences as $coachReference) {
                $this->messageBus->dispatch(new ImportEspnCoachMessage(
                    reference: $coachReference,
                    seasonId: $seasonId,
                    importEntities: $importEntities,
                ));
            }
        }

        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_INJURIES)) {
            $this->messageBus->dispatch(new ImportEspnTeamInjuriesMessage(
                teamId: $espnTeam->getId(),
                importEntities: $importEntities,
            ));
        }
    }
}
