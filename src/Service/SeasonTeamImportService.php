<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnSeasonTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportNotImplementedException;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTeamImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeGroupImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeTeamRecordImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeWeekImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;
use Symfony\Component\Stopwatch\Stopwatch;

class SeasonTeamImportService
{
    public const IMPORT_ENTITY_RECORDS = 'import_entity_records';
    public const IMPORT_ENTITY_RECORD_STATS = 'import_entity_record_stats';
    public const IMPORT_ENTITY_FRANCHISE = 'import_entity_franchise';
    public const IMPORT_ENTITY_FRANCHISE_VENUE = 'import_entity_frnachise_venue';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EspnSeasonTeamImporter       $espnSeasonTeamImporter,
        private readonly EspnSeasonTypeTeamRecordImporter $espnSeasonTypeTeamRecordImporter,
        private readonly EspnSeasonTypeRepository $espnSeasonTypeRepository,
    )
    {
    }

    public static function getDefaultImportEntities(): array
    {
        return [
            self::IMPORT_ENTITY_RECORDS => true,
            self::IMPORT_ENTITY_RECORD_STATS => true,
            self::IMPORT_ENTITY_FRANCHISE => true,
            self::IMPORT_ENTITY_FRANCHISE_VENUE => true,
        ];
    }

    public function importEspnSeasonTeams(int $year, ?array $importEntities = null): array
    {
        if (!$importEntities) {
            $importEntities = $this->getDefaultImportEntities();
        }

        $sections = [];

        $teamRefs = $this->espnSeasonTeamImporter->getSeasonTeamReferences($year);

        foreach ($teamRefs as $teamRef) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $teamRef,
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TEAM
            );

            $sections[$urlParams->teamId] = $this->importEspnSeasonTeam(
                $year,
                $urlParams->teamId
            );
        }

        return $sections;
    }

    public function importEspnSeasonTeam(int $year, int $teamId, ?array $importEntities = null): array
    {
        if (!$importEntities) {
            $importEntities = $this->getDefaultImportEntities();
        }

        $this->entityManager->beginTransaction();

        $stopwatch = new Stopwatch();
        $stopwatch->start(sprintf('import_espn_team_%s', $teamId));

        $stopwatch->openSection();
        $espnSeasonTeam = $this->espnSeasonTeamImporter->import($year, $teamId);

        $this->entityManager->persist($espnSeasonTeam);
        $this->entityManager->flush();
        $stopwatch->stopSection('team');

        $stopwatch->openSection();
        if (array_key_exists(self::IMPORT_ENTITY_RECORDS, $importEntities)) {
            foreach(EspnSeasonTypeEnum::cases() as $seasonTypeId) {
                $seasonType = $this->espnSeasonTypeRepository->findOneBy([
                    'year' => $year,
                    'type' => $seasonTypeId
                ]);

                foreach($this->espnSeasonTypeTeamRecordImporter->importForType($year, $seasonType, $espnSeasonTeam) as $record) {
                    $seasonType->addOrReplaceRecord($record);
                    $espnSeasonTeam->addOrReplaceRecord($record);
                }

                $this->entityManager->persist($seasonType);
                $this->entityManager->persist($espnSeasonTeam);
                $this->entityManager->flush();
            }

            $this->entityManager->persist($espnSeasonTeam);
            $this->entityManager->flush();
        }
        $stopwatch->stopSection('records');


//        $stopwatch->openSection();
//        $espnSeason = $this->espnSeasonImporter->import($year);
//        $stopwatch->stopSection('season');
//

//
//        $stopwatch->openSection();
//        if (array_key_exists(self::IMPORT_ENTITY_TYPES, $importEntities)) {
//            $espnSeason = $this->espnSeasonTypeImporter->importAll($espnSeason);
//        }
//        $stopwatch->stopSection('types');
//
//        $stopwatch->openSection();
//        if (array_key_exists(self::IMPORT_ENTITY_TYPE_GROUPS, $importEntities)) {
//            $espnSeason = $this->espnSeasonTypeGroupImporter->importAll($espnSeason);
//        }
//        $stopwatch->stopSection('type_groups');
//
//        $stopwatch->openSection();
//        if (array_key_exists(self::IMPORT_ENTITY_TYPE_WEEKS, $importEntities)) {
//            $espnSeason = $this->espnSeasonTypeWeekImporter->importAll($espnSeason);
//        }
//        $stopwatch->stopSection('type_weeks');

        $this->entityManager->flush();
        $this->entityManager->commit();

        $stopwatch->stop(sprintf('import_espn_team_%s', $teamId));

        return $stopwatch->getSections();
    }
}
