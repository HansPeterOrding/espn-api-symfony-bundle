<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class SyncEspnTeam
{
    public function __construct(
        public readonly string $espnTeamId
    ) {
    }
}
