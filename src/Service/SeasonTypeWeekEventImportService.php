<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnSeasonTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnEvent;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeWeek;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportConfigurationException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportNotImplementedException;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnEventCompetitionCompetitorImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnEventCompetitionCompetitorScoreImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnEventCompetitionImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnEventImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnFranchiseImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTeamImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeGroupImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeTeamRecordImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeWeekImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnVenueImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeWeekRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;
use Symfony\Component\Stopwatch\Stopwatch;

class SeasonTypeWeekEventImportService
{
    public const IMPORT_ENTITY_SEASON_TYPE_WEEK = 'import_entity_season_type_week';
    public const IMPORT_ENTITY_SEASON_TYPE_WEEK_EVENTS = 'import_entity_season_type_week_events';
    public const IMPORT_ENTITY_EVENT_COMPETITION = 'import_entity_event_competition';
    public const IMPORT_ENTITY_EVENT_COMPETITION_COMPETITORS = 'import_entity_event_competition_competitors';
    public const IMPORT_ENTITY_EVENT_COMPETITION_COMPETITOR_SCORE = 'import_entity_event_competition_competitor_score';

    public function __construct(
        private readonly EntityManagerInterface       $entityManager,
        private readonly EspnEventImporter            $espnEventImporter,
        private readonly EspnEventCompetitionImporter $espnEventCompetitionImporter,
        private readonly EspnEventCompetitionCompetitorImporter $espnEventCompetitionCompetitorImporter,
        private readonly EspnEventCompetitionCompetitorScoreImporter $espnEventCompetitionCompetitorScoreImporter,
        private readonly EspnSeasonTypeWeekImporter   $espnSeasonTypeWeekImporter,
        private readonly EspnSeasonRepository         $espnSeasonRepository,
        private readonly EspnSeasonTypeWeekRepository $espnSeasonTypeWeekRepository,
    )
    {
    }

    public static function getDefaultImportEntities(): array
    {
        return [
            self::IMPORT_ENTITY_SEASON_TYPE_WEEK_EVENTS => array_column(EspnSeasonTypeEnum::cases(), 'value'),
            self::IMPORT_ENTITY_EVENT_COMPETITION => true,
            self::IMPORT_ENTITY_EVENT_COMPETITION_COMPETITORS => true,
            self::IMPORT_ENTITY_EVENT_COMPETITION_COMPETITOR_SCORE => true,
        ];
    }

    public function importEspnSeasonTypeWeekEvents(int $year, ?array $importEntities = null): array
    {
        if (!$importEntities) {
            $importEntities = $this->getDefaultImportEntities();
        }

        $season = $this->espnSeasonRepository->findOneBy(['year' => $year]);
        if (!$season) {
            throw new ImportException(sprintf('You have to import EspnSeason %s first.', $year));
        }

        $sections = [];

        foreach ($importEntities[self::IMPORT_ENTITY_SEASON_TYPE_WEEK_EVENTS] as $typeId) {
            if (null === ($espnSeasonType = $season->getTypeByTypeId($typeId))) {
                /**
                 * @todo: yield message "Season type X skipped"
                 * @todo: yield messages for command execution in general
                 * @todo: is this class named correctly? Can we import events independent of weeks?
                 */
                continue;
            }


            /**
             * @todo: import events for all existing weeks of typeId
             */
            $weekRefs = $this->espnSeasonTypeWeekImporter->getSeasonTypeWeekReferences($year, $typeId);

            foreach ($weekRefs as $weekRef) {
                $urlParamsWeek = EspnUrlPatternResolver::resolveAll(
                    $weekRef,
                    EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_WEEK
                );

                if (null === ($espnSeasonTypeWeek = $espnSeasonType->getWeekByTypeAndWeekNumber($typeId, $urlParamsWeek->weekNumber))) {
                    /**
                     * @todo: yield message "Week X for season type Y skipped"
                     */
                    continue;
                }

                $eventRefs = $this->espnEventImporter->getEventReferences($urlParamsWeek->year, $urlParamsWeek->typeId, $urlParamsWeek->weekNumber);

                foreach ($eventRefs as $eventRef) {
                    $urlParamsEvent = EspnUrlPatternResolver::resolveAll(
                        $eventRef,
                        EspnUrlPatternResolver::URL_PATTERN_EVENT
                    );
                    $this->importEspnEvent(
                        $espnSeasonTypeWeek,
                        $urlParamsEvent->eventId,
                    );
                }
            }
        }

        return $sections;
    }

    public function importEspnEvent(EspnSeasonTypeWeek $espnSeasonTypeWeek, int $eventId, ?array $importEntities = null): array
    {
        if (!$importEntities) {
            $importEntities = $this->getDefaultImportEntities();
        }

        $this->entityManager->beginTransaction();

        $stopwatch = new Stopwatch();
        $stopwatch->start(sprintf('import_espn_event_%s', $eventId));

        $stopwatch->openSection();
        $espnEvent = $this->espnEventImporter->import($eventId);
        $espnEvent->setSeasonTypeWeek($espnSeasonTypeWeek);

        $this->entityManager->persist($espnEvent);
        $this->entityManager->flush();
        $stopwatch->stopSection('event');

        /**
         * @todo: import sub objects (competition, competitors, scores)
         */
        $stopwatch->openSection();
        if (array_key_exists(self::IMPORT_ENTITY_EVENT_COMPETITION, $importEntities)) {
            foreach ($this->espnEventCompetitionImporter->importForEvent($espnEvent) as $competition) {
                if (array_key_exists(self::IMPORT_ENTITY_EVENT_COMPETITION_COMPETITORS, $importEntities)) {
                    foreach($this->espnEventCompetitionCompetitorImporter->importForCompetition($competition) as $competitor) {
                        if(array_key_exists(self::IMPORT_ENTITY_EVENT_COMPETITION_COMPETITOR_SCORE, $importEntities)) {
                            $competitor->setScore(
                                $this->espnEventCompetitionCompetitorScoreImporter->importForCompetitor($competitor)
                            );
                        }

                        $competition->addOrReplaceCompetitor($competitor);
                    }
                }

                $espnEvent->addOrReplaceCompetition($competition);

                $this->entityManager->persist($espnEvent);
                $this->entityManager->flush();
            }
        }
        $stopwatch->stopSection('competitions');
//
//        $stopwatch->openSection();
//        if (array_key_exists(self::IMPORT_ENTITY_FRANCHISE, $importEntities)) {
//            $espnSeasonTeam->setFranchise(
//                $this->espnFranchiseImporter->import($espnSeasonTeam)
//            );
//
//            $this->entityManager->persist($espnSeasonTeam);
//            $this->entityManager->flush();
//        }
//        $stopwatch->stopSection('franchise');
//
//        $stopwatch->openSection();
//        if (array_key_exists(self::IMPORT_ENTITY_VENUE, $importEntities)) {
//            $espnVenue = $this->espnVenueImporter->importForTeam($espnSeasonTeam);
//            if ($espnSeasonTeam->getFranchise()) {
//                $espnVenue->addOrReplaceFranchise($espnSeasonTeam->getFranchise());
//            }
//
//            $espnSeasonTeam->setVenue($espnVenue);
//
//            $this->entityManager->persist($espnSeasonTeam);
//            $this->entityManager->flush();
//        }
//        $stopwatch->stopSection('venue');
//
//        $this->entityManager->flush();
        $this->entityManager->commit();

        $stopwatch->stop(sprintf('import_espn_event_%s', $eventId));

        return $stopwatch->getSections();
    }
}
