<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnSeasonTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportConfigurationException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportNotImplementedException;
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
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;
use Symfony\Component\Stopwatch\Stopwatch;

class SeasonTeamImportService
{
    public const IMPORT_ENTITY_RECORDS = 'import_entity_records';
    public const IMPORT_ENTITY_FRANCHISE = 'import_entity_franchise';
    public const IMPORT_ENTITY_VENUE = 'import_entity_venue';

    public function __construct(
        private readonly EntityManagerInterface           $entityManager,
        private readonly EspnSeasonTeamImporter           $espnSeasonTeamImporter,
        private readonly EspnSeasonTypeTeamRecordImporter $espnSeasonTypeTeamRecordImporter,
        private readonly EspnFranchiseImporter            $espnFranchiseImporter,
        private readonly EspnSeasonRepository             $espnSeasonRepository,
        private readonly EspnSeasonTypeRepository         $espnSeasonTypeRepository, private readonly EspnVenueImporter $espnVenueImporter,
    )
    {
    }

    public static function getDefaultImportEntities(): array
    {
        return [
//            self::IMPORT_ENTITY_RECORDS => true,
            self::IMPORT_ENTITY_FRANCHISE => true,
            self::IMPORT_ENTITY_VENUE => true,
        ];
    }

    public function importEspnSeasonTeams(int $year, ?array $importEntities = null): array
    {
        if (!$importEntities) {
            $importEntities = $this->getDefaultImportEntities();
        }

        $season = $this->espnSeasonRepository->findOneBy(['year' => $year]);
        if(!$season) {
            throw new ImportException(sprintf('You have to import EspnSeason %s first.', $year));
        }

        $sections = [];

        $teamRefs = $this->espnSeasonTeamImporter->getSeasonTeamReferences($year);

        foreach ($teamRefs as $teamRef) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $teamRef,
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TEAM
            );

            $sections[$urlParams->teamId] = $this->importEspnSeasonTeam(
                $season,
                $urlParams->teamId
            );
        }

        return $sections;
    }

    public function importEspnSeasonTeam(EspnSeason $espnSeason, int $teamId, ?array $importEntities = null): array
    {
        if (!$importEntities) {
            $importEntities = $this->getDefaultImportEntities();
        }

        $year = $espnSeason->getYear();

        $this->entityManager->beginTransaction();

        $stopwatch = new Stopwatch();
        $stopwatch->start(sprintf('import_espn_team_%s', $teamId));

        $stopwatch->openSection();
        $espnSeasonTeam = $this->espnSeasonTeamImporter->import($espnSeason, $teamId);
        $espnSeasonTeam->setSeason($espnSeason);

        $this->entityManager->persist($espnSeasonTeam);
        $this->entityManager->flush();
        $stopwatch->stopSection('team');

        $stopwatch->openSection();
        if (array_key_exists(self::IMPORT_ENTITY_RECORDS, $importEntities)) {
            foreach (EspnSeasonTypeEnum::cases() as $seasonTypeId) {
                $seasonType = $this->espnSeasonTypeRepository->findOneBy([
                    'year' => $year,
                    'type' => $seasonTypeId
                ]);

                foreach ($this->espnSeasonTypeTeamRecordImporter->importForType($year, $seasonType, $espnSeasonTeam) as $record) {
                    $seasonType->addOrReplaceRecord($record);
                    $espnSeasonTeam->addOrReplaceRecord($record);

                    $this->entityManager->persist($seasonType);
                    $this->entityManager->persist($espnSeasonTeam);
                    $this->entityManager->flush();
                }
            }
        }
        $stopwatch->stopSection('records');

        $stopwatch->openSection();
        if (array_key_exists(self::IMPORT_ENTITY_FRANCHISE, $importEntities)) {
            $espnSeasonTeam->setFranchise(
                $this->espnFranchiseImporter->import($espnSeasonTeam)
            );

            $this->entityManager->persist($espnSeasonTeam);
            $this->entityManager->flush();
        }
        $stopwatch->stopSection('franchise');

        $stopwatch->openSection();
        if (array_key_exists(self::IMPORT_ENTITY_VENUE, $importEntities)) {
            $espnVenue = $this->espnVenueImporter->importForTeam($espnSeasonTeam);
            if ($espnSeasonTeam->getFranchise()) {
                $espnVenue->addOrReplaceFranchise($espnSeasonTeam->getFranchise());
            }

            $espnSeasonTeam->setVenue($espnVenue);

            $this->entityManager->persist($espnSeasonTeam);
            $this->entityManager->flush();
        }
        $stopwatch->stopSection('venue');

        $this->entityManager->flush();
        $this->entityManager->commit();

        $stopwatch->stop(sprintf('import_espn_team_%s', $teamId));

        return $stopwatch->getSections();
    }
}
