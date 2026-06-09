<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Util;

class EspnUrlPatternValues
{
    public function __construct(
        public ?int $eventId = null,
        public ?int $competitionId = null,
        public ?int $competitorId = null,
        public ?int $franchiseId = null,
        public ?int $venueId = null,
        public ?int $year = null,
        public ?int $typeId = null,
        public ?int $groupId = null,
        public ?int $weekNumber = null,
        public ?int $teamId = null,
        public ?int $recordId = null,
        public ?int $athleteId = null,
        public ?int $coachId = null,
        public ?int $standingId = null,
        public ?int $officialId = null,
        public ?int $positionId = null,
        public ?int $injuryId = null,
        public ?int $contractYear = null,
    ) {
    }
}
