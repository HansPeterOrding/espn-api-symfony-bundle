<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

use HansPeterOrding\EspnApiClient\Dto\EspnTeam;

class SyncEspnTeam
{
    public function __construct(
        public readonly EspnTeam $espnTeam
    ) {
    }
}
