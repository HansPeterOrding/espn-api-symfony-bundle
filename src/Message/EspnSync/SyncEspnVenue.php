<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

use HansPeterOrding\EspnApiClient\Dto\EspnVenue;

class SyncEspnVenue
{
    public function __construct(
        public readonly EspnVenue $espnVenue
    ) {
    }
}
