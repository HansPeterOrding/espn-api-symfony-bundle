<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class SyncEspnVenue
{
    public function __construct(
        public readonly string $espnVenueId
    ) {
    }
}
