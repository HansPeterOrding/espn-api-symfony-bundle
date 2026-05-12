<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Util;

use HansPeterOrding\EspnApiSymfonyBundle\Exception\EspnUrlPatternResolverMismatchException;

class EspnUrlPatternResolver
{
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
    const URL_PATTERN_SEASON_TYPE_TEAMS = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/teams/';
    const URL_PATTERN_SEASON_TYPE_TEAM = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/teams\/(?<teamId>\d+)/';
    const URL_PATTERN_SEASON_TYPE_TEAM_RECORDS = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/teams\/(?<teamId>\d+)\/record/';
    const URL_PATTERN_SEASON_TYPE_TEAM_RECORD = '/seasons\/(?<year>\d+)\/types\/(?<typeId>\d+)\/teams\/(?<teamId>\d+)\/records\/(?<recordId>\d+)/';

    const URL_ATTRIBUTE_FRANCHISE_ID = 'franchiseId';
    const URL_ATTRIBUTE_VENUE_ID = 'venueId';
    const URL_ATTRIBUTE_YEAR = 'year';
    const URL_ATTRIBUTE_TYPE_ID = 'typeId';
    const URL_ATTRIBUTE_GROUP_ID = 'groupId';
    const URL_ATTRIBUTE_WEEK_NUMBER = 'weekNumber';
    const URL_ATTRIBUTE_TEAM_ID = 'teamId';
    const URL_ATTRIBUTE_RECORD_ID = 'recordId';

    private static array $allowedPatterns = [
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
        self::URL_PATTERN_SEASON_TYPE_TEAMS,
        self::URL_PATTERN_SEASON_TYPE_TEAM,
        self::URL_PATTERN_SEASON_TYPE_TEAM_RECORDS,
        self::URL_PATTERN_SEASON_TYPE_TEAM_RECORD,
    ];
    private static array $allowedAttributes = [
        self::URL_ATTRIBUTE_FRANCHISE_ID,
        self::URL_ATTRIBUTE_VENUE_ID,
        self::URL_ATTRIBUTE_YEAR,
        self::URL_ATTRIBUTE_TYPE_ID,
        self::URL_ATTRIBUTE_GROUP_ID,
        self::URL_ATTRIBUTE_WEEK_NUMBER,
        self::URL_ATTRIBUTE_TEAM_ID,
        self::URL_ATTRIBUTE_RECORD_ID,
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

    public static function resolveAll(string $espnApiUrl, string $pattern)
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
            $cast(self::URL_ATTRIBUTE_FRANCHISE_ID),
            $cast(self::URL_ATTRIBUTE_VENUE_ID),
            $cast(self::URL_ATTRIBUTE_YEAR),
            $cast(self::URL_ATTRIBUTE_TYPE_ID),
            $cast(self::URL_ATTRIBUTE_GROUP_ID),
            $cast(self::URL_ATTRIBUTE_WEEK_NUMBER),
            $cast(self::URL_ATTRIBUTE_TEAM_ID),
            $cast(self::URL_ATTRIBUTE_RECORD_ID),
        );
    }
}
