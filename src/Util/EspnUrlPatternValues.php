<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Util;

class EspnUrlPatternValues
{
    public function __construct(
        public ?int $franchiseId = null,
        public ?int $venueId = null,
        public ?int $year = null,
        public ?int $typeId = null,
        public ?int $groupId = null,
        public ?int $weekNumber = null,
        public ?int $teamId = null,
        public ?int $recordId = null
    )
    {
    }
}
