<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportNotImplementedException;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeGroupImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonTypeWeekImporter;
use Symfony\Component\Stopwatch\Stopwatch;

class SeasonImportService
{
    public const IMPORT_ENTITY_TYPE = 'import_entity_type';
    public const IMPORT_ENTITY_TYPES = 'import_entity_types';
    public const IMPORT_ENTITY_RANKINGS = 'import_entity_rankings';
    public const IMPORT_ENTITY_FUTURES = 'import_entity_futures';
    public const IMPORT_ENTITY_TYPE_GROUPS = 'import_entity_type_groups';
    public const IMPORT_ENTITY_TYPE_WEEKS = 'import_entity_type_weeks';

    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly EspnSeasonImporter          $espnSeasonImporter,
        private readonly EspnSeasonTypeImporter      $espnSeasonTypeImporter,
        private readonly EspnSeasonTypeGroupImporter $espnSeasonTypeGroupImporter,
        private readonly EspnSeasonTypeWeekImporter  $espnSeasonTypeWeekImporter,
    )
    {
    }

    public static function getDefaultImportEntities(): array
    {
        return [
            self::IMPORT_ENTITY_TYPE => true,
            self::IMPORT_ENTITY_TYPES => true,
            self::IMPORT_ENTITY_TYPE_GROUPS => true,
            self::IMPORT_ENTITY_TYPE_WEEKS => true,
            self::IMPORT_ENTITY_RANKINGS => false,
            self::IMPORT_ENTITY_FUTURES => false,
        ];
    }

    public function importEspnLeague(int $year, ?array $importEntities = null): array
    {
        if (!$importEntities) {
            $importEntities = $this->getDefaultImportEntities();
        }

        if (array_key_exists(self::IMPORT_ENTITY_RANKINGS, $importEntities) && $importEntities[self::IMPORT_ENTITY_RANKINGS] !== false) {
            throw new ImportNotImplementedException(self::IMPORT_ENTITY_RANKINGS);
        }
        if (array_key_exists(self::IMPORT_ENTITY_FUTURES, $importEntities) && $importEntities[self::IMPORT_ENTITY_FUTURES] !== false) {
            throw new ImportNotImplementedException(self::IMPORT_ENTITY_FUTURES);
        }

        $this->entityManager->beginTransaction();

        $stopwatch = new Stopwatch();
        $stopwatch->start('import_espn_season');

        $stopwatch->openSection();
        $espnSeason = $this->espnSeasonImporter->import($year);
        $this->entityManager->persist($espnSeason);
        $this->entityManager->flush();
        $stopwatch->stopSection('season');

        $stopwatch->openSection();
        if (array_key_exists(self::IMPORT_ENTITY_TYPE, $importEntities)) {
            $espnSeason->setType(
                $this->espnSeasonTypeImporter->import($espnSeason)
            );
            $this->entityManager->persist($espnSeason);
            $this->entityManager->flush();
        }
        $stopwatch->stopSection('type');

        $stopwatch->openSection();
        if (array_key_exists(self::IMPORT_ENTITY_TYPES, $importEntities)) {
            foreach ($this->espnSeasonTypeImporter->importAll($espnSeason) as $type) {
                $espnSeason->addOrReplaceType($type);
            }
            $this->entityManager->persist($espnSeason);
            $this->entityManager->flush();
        }
        $stopwatch->stopSection('types');

        $stopwatch->openSection();
        if (array_key_exists(self::IMPORT_ENTITY_TYPE_GROUPS, $importEntities)) {
            foreach ($this->espnSeasonTypeGroupImporter->importForType($year, $espnSeason->getType()) as $group) {
                $espnSeason->getType()->addOrReplaceGroup($group);
            }
            $this->entityManager->persist($espnSeason);
            $this->entityManager->flush();

            foreach ($espnSeason->getTypes() as $type) {
                foreach ($this->espnSeasonTypeGroupImporter->importForType($year, $type) as $group) {
                    $type->addOrReplaceGroup($group);
                }
                $this->entityManager->persist($espnSeason);
                $this->entityManager->flush();
            }
        }
        $stopwatch->stopSection('type_groups');

        $stopwatch->openSection();
        if (array_key_exists(self::IMPORT_ENTITY_TYPE_WEEKS, $importEntities)) {
            foreach ($this->espnSeasonTypeWeekImporter->importForType($year, $espnSeason->getType()) as $week) {
                $espnSeason->getType()->addOrReplaceWeek($week);
            }
            $this->entityManager->persist($espnSeason);
            $this->entityManager->flush();

            foreach ($espnSeason->getTypes() as $type) {
                foreach ($this->espnSeasonTypeWeekImporter->importForType($year, $type) as $week) {
                    $type->addOrReplaceWeek($week);
                }
                $this->entityManager->persist($espnSeason);
                $this->entityManager->flush();
            }
        }
        $stopwatch->stopSection('type_weeks');

        $this->entityManager->flush();
        $this->entityManager->commit();

        $stopwatch->stop('import_espn_season');

        return $stopwatch->getSections();
    }
}
