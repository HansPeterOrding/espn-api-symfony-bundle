<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

use HansPeterOrding\EspnApiClient\Dto\EspnSchedule;

class SyncEspnSchedule
{
    public function __construct(
        public readonly EspnSchedule $espnSchedule
    ) {
    }
}
