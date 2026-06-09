<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Util;

use HansPeterOrding\EspnApiSymfonyBundle\Exception\EspnUrlPatternResolverMismatchException;

class EspnUrlPatternResolver
{
    // Existing patterns
    const URL_PATTERN_EVENT = '/events\/(?<eventId>\d+)/';
    const URL_PATTERN_EVENT_COMPETITIONS = '/events\/(?<eventId>\d+)\/competitions/';
    const URL_PATTERN_EVENT_COMPETITION = '/events\/(?<eventId>\d+)\/competitions\/(?<competitionId>\d+)/';
    const URL_PATTERN_EVENT_COMPETITION_COMPETITORS = '/events\/(?<eventId>\d+)\/competitions\/(?<competitionId>\d+)\/competitors/';
    const URL_PATTERN_EVENT_COMPETITION_COMPETITOR = '/events\/(?<eventId>\d+)\/competitions\/(?<competitionId>\d+)\/competitors\/(?<competitorId>\d+)/';
    const URL_PATTERN_EVENT_COMPETITION_COMPETITOR_SCORE = '/events\/(?<eventId>\d+)\/competitions\/(?<competitionId>\d+)\/competitors\/(?<competitorId>\d+)\/score/';
    const URL_PATTERN_FRANCHISES = '/franchises/';
    const URL_PATTERN_FRANCHISE = '/franchises\/(?<franchiseId>\d+)/';
    const URL_PATTERN_VENUES = '/venues/';
    const URL_PATTERN_VENUE = '/venues\/(?<venueId>\d+)/';
    const URL_PATTERN_SEASONS = '/seasons/';
    const URL_PATTERN_SEASON = '/seasons\/(?<year>\d+)/';
    const URL_PATTERN_SEASON_TEAMS = '/seasons\/(?<year>\d+)\/teams/';
    const URL_PATTERN_SEASON_TEAM = '/seasons\/(?<year>\d+)\/teams\/(?<teamId>\d+)/';
    const URL_PATTERN_SEASON_TYPES = '/seasons\/(?<year>\d+)\/types/';
    const URL_PATTERN_SEASON_TYPE = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)/';
    const URL_PATTERN_SEASON_TYPE_GROUPS = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/groups/';
    const URL_PATTERN_SEASON_TYPE_GROUP = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/groups\/(?<groupId>\d+)/';
    const URL_PATTERN_SEASON_TYPE_WEEKS = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/weeks/';
    const URL_PATTERN_SEASON_TYPE_WEEK = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/weeks\/(?<weekNumber>\d+)/';
    const URL_PATTERN_SEASON_TYPE_WEEK_EVENTS = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/weeks\/(?<weekNumber>\d+)\/events/';
    const URL_PATTERN_SEASON_TYPE_GROUP_TEAMS = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/groups\/(?<groupId>\d+)\/teams/';
    const URL_PATTERN_SEASON_TYPE_GROUP_TEAM = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/groups\/(?<groupId>\d+)\/teams\/(?<teamId>\d+)/';
    const URL_PATTERN_SEASON_TYPE_TEAM_RECORDS = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/teams\/(?<teamId>\d+)\/record/';
    const URL_PATTERN_SEASON_TYPE_TEAM_RECORD = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/teams\/(?<teamId>\d+)\/records\/(?<recordId>\d+)/';
    const URL_PATTERN_SEASON_TYPE_GROUP_TEAM_RECORDS = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/groups\/(?<groupId>\d+)\/teams\/(?<teamId>\d+)\/records/';
    const URL_PATTERN_SEASON_TYPE_GROUP_TEAM_RECORD = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/groups\/(?<groupId>\d+)\/teams\/(?<teamId>\d+)\/records\/(?<recordId>\d+)/';

    // New patterns
    const URL_PATTERN_SEASON_ATHLETES = '/seasons\/(?<year>\d+)\/athletes/';
    const URL_PATTERN_SEASON_ATHLETE = '/seasons\/(?<year>\d+)\/athletes\/(?<athleteId>\d+)/';
    const URL_PATTERN_ATHLETE_CONTRACT = '/athletes\/(?<athleteId>\d+)\/contracts\/(?<contractYear>\d+)/';
    const URL_PATTERN_SEASON_TEAM_ATHLETES = '/seasons\/(?<year>\d+)\/teams\/(?<teamId>\d+)\/athletes/';
    const URL_PATTERN_SEASON_ATHLETE_NOTES = '/seasons\/(?<year>\d+)\/athletes\/(?<athleteId>\d+)\/notes/';
    const URL_PATTERN_SEASON_ATHLETE_INJURIES = '/seasons\/(?<year>\d+)\/athletes\/(?<athleteId>\d+)\/injuries/';
    const URL_PATTERN_SEASON_ATHLETE_INJURY = '/seasons\/(?<year>\d+)\/athletes\/(?<athleteId>\d+)\/injuries\/(?<injuryId>-?\d+)/';
    const URL_PATTERN_SEASON_COACHES = '/seasons\/(?<year>\d+)\/coaches/';
    const URL_PATTERN_SEASON_COACH = '/seasons\/(?<year>\d+)\/coaches\/(?<coachId>\d+)/';
    const URL_PATTERN_SEASON_TEAM_COACHES = '/seasons\/(?<year>\d+)\/teams\/(?<teamId>\d+)\/coaches/';
    const URL_PATTERN_SEASON_TYPE_GROUP_CHILDREN = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/groups\/(?<groupId>\d+)\/children/';
    const URL_PATTERN_SEASON_TYPE_GROUP_STANDINGS = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/groups\/(?<groupId>\d+)\/standings/';
    const URL_PATTERN_SEASON_TYPE_TEAM_GROUPS = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/teams\/(?<teamId>\d+)\/groups/';
    const URL_PATTERN_SEASON_TYPE_GROUP_STANDING = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/groups\/(?<groupId>\d+)\/standings\/(?<standingId>\d+)/';
    const URL_PATTERN_EVENT_COMPETITION_OFFICIALS = '/events\/(?<eventId>\d+)\/competitions\/(?<competitionId>\d+)\/officials/';
    const URL_PATTERN_EVENT_COMPETITION_OFFICIAL = '/events\/(?<eventId>\d+)\/competitions\/(?<competitionId>\d+)\/officials\/(?<officialId>\d+)/';
    const URL_PATTERN_EVENT_COMPETITION_STATUS = '/events\/(?<eventId>\d+)\/competitions\/(?<competitionId>\d+)\/status/';
    const URL_PATTERN_POSITIONS = '/positions/';
    const URL_PATTERN_POSITION = '/positions\/(?<positionId>\d+)/';

    const URL_ATTRIBUTE_EVENT_ID = 'eventId';
    const URL_ATTRIBUTE_COMPETITION_ID = 'competitionId';
    const URL_ATTRIBUTE_COMPETITOR_ID = 'competitorId';
    const URL_ATTRIBUTE_FRANCHISE_ID = 'franchiseId';
    const URL_ATTRIBUTE_VENUE_ID = 'venueId';
    const URL_ATTRIBUTE_YEAR = 'year';
    const URL_ATTRIBUTE_TYPE_ID = 'typeId';
    const URL_ATTRIBUTE_GROUP_ID = 'groupId';
    const URL_ATTRIBUTE_WEEK_NUMBER = 'weekNumber';
    const URL_ATTRIBUTE_TEAM_ID = 'teamId';
    const URL_ATTRIBUTE_RECORD_ID = 'recordId';
    const URL_ATTRIBUTE_ATHLETE_ID = 'athleteId';
    const URL_ATTRIBUTE_COACH_ID = 'coachId';
    const URL_ATTRIBUTE_STANDING_ID = 'standingId';
    const URL_ATTRIBUTE_OFFICIAL_ID = 'officialId';
    const URL_ATTRIBUTE_POSITION_ID = 'positionId';
    const URL_ATTRIBUTE_INJURY_ID = 'injuryId';
    const URL_ATTRIBUTE_CONTRACT_YEAR = 'contractYear';

    private static array $allowedPatterns = [
        self::URL_PATTERN_EVENT,
        self::URL_PATTERN_EVENT_COMPETITIONS,
        self::URL_PATTERN_EVENT_COMPETITION,
        self::URL_PATTERN_EVENT_COMPETITION_COMPETITOR,
        self::URL_PATTERN_EVENT_COMPETITION_COMPETITORS,
        self::URL_PATTERN_EVENT_COMPETITION_COMPETITOR_SCORE,
        self::URL_PATTERN_EVENT_COMPETITION_OFFICIALS,
        self::URL_PATTERN_EVENT_COMPETITION_OFFICIAL,
        self::URL_PATTERN_EVENT_COMPETITION_STATUS,
        self::URL_PATTERN_FRANCHISES,
        self::URL_PATTERN_FRANCHISE,
        self::URL_PATTERN_VENUES,
        self::URL_PATTERN_VENUE,
        self::URL_PATTERN_SEASONS,
        self::URL_PATTERN_SEASON,
        self::URL_PATTERN_SEASON_TEAMS,
        self::URL_PATTERN_SEASON_TEAM,
        self::URL_PATTERN_SEASON_TYPES,
        self::URL_PATTERN_SEASON_TYPE,
        self::URL_PATTERN_SEASON_TYPE_GROUPS,
        self::URL_PATTERN_SEASON_TYPE_GROUP,
        self::URL_PATTERN_SEASON_TYPE_WEEKS,
        self::URL_PATTERN_SEASON_TYPE_WEEK,
        self::URL_PATTERN_SEASON_TYPE_WEEK_EVENTS,
        self::URL_PATTERN_SEASON_TYPE_GROUP_TEAMS,
        self::URL_PATTERN_SEASON_TYPE_GROUP_TEAM,
        self::URL_PATTERN_SEASON_TYPE_TEAM_RECORDS,
        self::URL_PATTERN_SEASON_TYPE_TEAM_RECORD,
        self::URL_PATTERN_SEASON_TYPE_GROUP_TEAM_RECORDS,
        self::URL_PATTERN_SEASON_TYPE_GROUP_TEAM_RECORD,
        self::URL_PATTERN_SEASON_ATHLETES,
        self::URL_PATTERN_SEASON_ATHLETE,
        self::URL_PATTERN_ATHLETE_CONTRACT,
        self::URL_PATTERN_SEASON_TEAM_ATHLETES,
        self::URL_PATTERN_SEASON_ATHLETE_NOTES,
        self::URL_PATTERN_SEASON_ATHLETE_INJURIES,
        self::URL_PATTERN_SEASON_ATHLETE_INJURY,
        self::URL_PATTERN_SEASON_COACHES,
        self::URL_PATTERN_SEASON_COACH,
        self::URL_PATTERN_SEASON_TEAM_COACHES,
        self::URL_PATTERN_SEASON_TYPE_GROUP_CHILDREN,
        self::URL_PATTERN_SEASON_TYPE_GROUP_STANDINGS,
        self::URL_PATTERN_SEASON_TYPE_TEAM_GROUPS,
        self::URL_PATTERN_SEASON_TYPE_GROUP_STANDING,
        self::URL_PATTERN_POSITIONS,
        self::URL_PATTERN_POSITION,
    ];

    private static array $allowedAttributes = [
        self::URL_ATTRIBUTE_EVENT_ID,
        self::URL_ATTRIBUTE_COMPETITION_ID,
        self::URL_ATTRIBUTE_COMPETITOR_ID,
        self::URL_ATTRIBUTE_FRANCHISE_ID,
        self::URL_ATTRIBUTE_VENUE_ID,
        self::URL_ATTRIBUTE_YEAR,
        self::URL_ATTRIBUTE_TYPE_ID,
        self::URL_ATTRIBUTE_GROUP_ID,
        self::URL_ATTRIBUTE_WEEK_NUMBER,
        self::URL_ATTRIBUTE_TEAM_ID,
        self::URL_ATTRIBUTE_RECORD_ID,
        self::URL_ATTRIBUTE_ATHLETE_ID,
        self::URL_ATTRIBUTE_COACH_ID,
        self::URL_ATTRIBUTE_STANDING_ID,
        self::URL_ATTRIBUTE_OFFICIAL_ID,
        self::URL_ATTRIBUTE_POSITION_ID,
        self::URL_ATTRIBUTE_INJURY_ID,
    ];

    public static function resolvePatternAttribute(string $espnApiUrl, string $pattern, string $attribute): ?int
    {
        if (!in_array($pattern, self::$allowedPatterns)) {
            throw new EspnUrlPatternResolverMismatchException(EspnUrlPatternResolverMismatchException::EXCEPTION_TYPE_PATTERN, $pattern);
        }

        if (!in_array($attribute, self::$allowedAttributes)) {
            throw new EspnUrlPatternResolverMismatchException(EspnUrlPatternResolverMismatchException::EXCEPTION_TYPE_ATTRIBUTE, $attribute);
        }

        return preg_match(
            $pattern,
            $espnApiUrl,
            $matches
        ) ? (int)$matches[$attribute] : null;
    }

    public static function resolveAll(string $espnApiUrl, string $pattern): EspnUrlPatternValues
    {
        if (!in_array($pattern, self::$allowedPatterns)) {
            throw new EspnUrlPatternResolverMismatchException(EspnUrlPatternResolverMismatchException::EXCEPTION_TYPE_PATTERN, $pattern);
        }

        $matched = preg_match($pattern, $espnApiUrl, $matches);

        if ($matched === 0 || $matched === false) {
            return new EspnUrlPatternValues();
        }

        $cast = fn($key) => isset($matches[$key]) ? (int)$matches[$key] : null;

        return new EspnUrlPatternValues(
            eventId: $cast(self::URL_ATTRIBUTE_EVENT_ID),
            competitionId: $cast(self::URL_ATTRIBUTE_COMPETITION_ID),
            competitorId: $cast(self::URL_ATTRIBUTE_COMPETITOR_ID),
            franchiseId: $cast(self::URL_ATTRIBUTE_FRANCHISE_ID),
            venueId: $cast(self::URL_ATTRIBUTE_VENUE_ID),
            year: $cast(self::URL_ATTRIBUTE_YEAR),
            typeId: $cast(self::URL_ATTRIBUTE_TYPE_ID),
            groupId: $cast(self::URL_ATTRIBUTE_GROUP_ID),
            weekNumber: $cast(self::URL_ATTRIBUTE_WEEK_NUMBER),
            teamId: $cast(self::URL_ATTRIBUTE_TEAM_ID),
            recordId: $cast(self::URL_ATTRIBUTE_RECORD_ID),
            athleteId: $cast(self::URL_ATTRIBUTE_ATHLETE_ID),
            coachId: $cast(self::URL_ATTRIBUTE_COACH_ID),
            standingId: $cast(self::URL_ATTRIBUTE_STANDING_ID),
            officialId: $cast(self::URL_ATTRIBUTE_OFFICIAL_ID),
            positionId: $cast(self::URL_ATTRIBUTE_POSITION_ID),
            injuryId: $cast(self::URL_ATTRIBUTE_INJURY_ID),
            contractYear: $cast(self::URL_ATTRIBUTE_CONTRACT_YEAR),
        );
    }
}
