<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;

trait ImportEntitiesHelperTrait
{
    private function shouldImport(array $importEntities, string $key): bool
    {
        if (!array_key_exists($key, $importEntities)) {
            return false;
        }

        return $importEntities[$key] !== false;
    }

    private function shouldImportSeasonGroups(array $importEntities, bool $isCurrent): bool
    {
        if (!array_key_exists(EspnImportService::IMPORT_ENTITY_SEASON_GROUPS, $importEntities)) {
            return false;
        }

        $config = $importEntities[EspnImportService::IMPORT_ENTITY_SEASON_GROUPS];

        if ($config === false) {
            return false;
        }

        if ($config === true) {
            return true;
        }

        if ($config === EspnImportService::IMPORT_SEASON_GROUPS_FOR_CURRENT_TYPE_ONLY) {
            return $isCurrent === true;
        }

        return false;
    }

    private function shouldImportTeamsFromSeason(array $importEntities): bool
    {
        if (!array_key_exists(EspnImportService::IMPORT_ENTITY_TEAMS, $importEntities)) {
            return false;
        }

        $config = $importEntities[EspnImportService::IMPORT_ENTITY_TEAMS];

        if ($config === false) {
            return false;
        }

        if ($config === true) {
            return true;
        }

        if (!is_array($config)) {
            return false;
        }

        $seasonConfig = $config[EspnImportService::IMPORT_TEAMS_LEVEL_SEASON] ?? false;

        return $seasonConfig === true;
    }

    private function shouldImportTeamsForGroup(array $importEntities, bool $isConference): bool
    {
        if (!array_key_exists(EspnImportService::IMPORT_ENTITY_TEAMS, $importEntities)) {
            return false;
        }

        $config = $importEntities[EspnImportService::IMPORT_ENTITY_TEAMS];

        if ($config === false) {
            return false;
        }

        if ($config === true) {
            return true;
        }

        if (!is_array($config) || !isset($config[EspnImportService::IMPORT_TEAMS_LEVEL_GROUP])) {
            return false;
        }

        $groupConfig = $config[EspnImportService::IMPORT_TEAMS_LEVEL_GROUP];

        if ($groupConfig === false) {
            return false;
        }

        if ($groupConfig === true) {
            return true;
        }

        if (is_array($groupConfig)) {
            $key = $isConference
                ? EspnImportService::IMPORT_TEAMS_GROUP_TYPE_CONFERENCE
                : EspnImportService::IMPORT_TEAMS_GROUP_TYPE_LEAF;

            return $groupConfig[$key] ?? false;
        }

        return false;
    }
}
