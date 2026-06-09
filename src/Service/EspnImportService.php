<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Service;

class EspnImportService
{
    // Season
    public const IMPORT_ENTITY_SEASON_TYPE = 'import_entity_season_type';
    public const IMPORT_ENTITY_SEASON_TYPES = 'import_entity_season_types';

    // SeasonType
    // IMPORT_ENTITY_SEASON_GROUPS accepts: false, true, or IMPORT_SEASON_GROUPS_FOR_CURRENT_TYPE_ONLY
    public const IMPORT_ENTITY_SEASON_GROUPS = 'import_entity_season_groups';
    public const IMPORT_SEASON_GROUPS_FOR_CURRENT_TYPE_ONLY = 'current';
    public const IMPORT_ENTITY_WEEKS = 'import_entity_weeks';

    // SeasonGroup
    public const IMPORT_ENTITY_STANDINGS = 'import_entity_standings';
    public const IMPORT_STANDINGS_TYPE_OVERALL = 'overall';
    public const IMPORT_STANDINGS_TYPE_PLAYOFF = 'playoff';
    public const IMPORT_STANDINGS_TYPE_EXPANDED = 'expanded';
    public const IMPORT_STANDINGS_TYPE_DIVISION = 'division';

    // Teams — dispatched from season or group level
    public const IMPORT_ENTITY_TEAMS = 'import_entity_teams';
    public const IMPORT_TEAMS_LEVEL_SEASON = 'season';
    public const IMPORT_TEAMS_LEVEL_GROUP = 'group';
    public const IMPORT_TEAMS_GROUP_TYPE_LEAF = 'leaf';
    public const IMPORT_TEAMS_GROUP_TYPE_CONFERENCE = 'conference';

    // Week
    public const IMPORT_ENTITY_EVENTS = 'import_entity_events';

    // Event
    public const IMPORT_ENTITY_COMPETITIONS = 'import_entity_competitions';

    // Competition (inline, no own message)
    public const IMPORT_ENTITY_COMPETITION_STATUS = 'import_entity_competition_status';

    // Competition
    public const IMPORT_ENTITY_COMPETITORS = 'import_entity_competitors';
    public const IMPORT_ENTITY_OFFICIALS = 'import_entity_officials';

    // Competitor
    public const IMPORT_ENTITY_SCORE = 'import_entity_score';

    // Team
    public const IMPORT_ENTITY_VENUE = 'import_entity_venue';
    public const IMPORT_ENTITY_FRANCHISE = 'import_entity_franchise';
    public const IMPORT_ENTITY_RECORDS = 'import_entity_records';
    public const IMPORT_ENTITY_ATHLETES = 'import_entity_athletes';
    public const IMPORT_ENTITY_COACHES = 'import_entity_coaches';

    // Athlete
    public const IMPORT_ENTITY_POSITION = 'import_entity_position';
    public const IMPORT_ENTITY_CONTRACT = 'import_entity_contract';
    public const IMPORT_ENTITY_INJURIES = 'import_entity_injuries';

    public static function getSeasonImportEntities(): array
    {
        return [
            self::IMPORT_ENTITY_SEASON_TYPES => true,
            self::IMPORT_ENTITY_SEASON_GROUPS => self::IMPORT_SEASON_GROUPS_FOR_CURRENT_TYPE_ONLY,
            self::IMPORT_ENTITY_WEEKS => true,
            self::IMPORT_ENTITY_TEAMS => [
                self::IMPORT_TEAMS_LEVEL_SEASON => false,
                self::IMPORT_TEAMS_LEVEL_GROUP => [
                    self::IMPORT_TEAMS_GROUP_TYPE_LEAF => true,
                    self::IMPORT_TEAMS_GROUP_TYPE_CONFERENCE => false,
                ],
            ],
            self::IMPORT_ENTITY_VENUE => true,
            self::IMPORT_ENTITY_FRANCHISE => true,
            self::IMPORT_ENTITY_RECORDS => true,
            self::IMPORT_ENTITY_STANDINGS => false,
            self::IMPORT_ENTITY_EVENTS => true,
            self::IMPORT_ENTITY_COMPETITIONS => true,
            self::IMPORT_ENTITY_COMPETITION_STATUS => true,
            self::IMPORT_ENTITY_COMPETITORS => true,
            self::IMPORT_ENTITY_SCORE => true,
            self::IMPORT_ENTITY_OFFICIALS => true,
            self::IMPORT_ENTITY_ATHLETES => true,
            self::IMPORT_ENTITY_COACHES => true,
            self::IMPORT_ENTITY_CONTRACT => true,
            self::IMPORT_ENTITY_POSITION => true,
            self::IMPORT_ENTITY_INJURIES => true,
        ];
    }

    public static function getPositionsImportEntities(): array
    {
        return [
            self::IMPORT_ENTITY_POSITION => true,
        ];
    }
}
