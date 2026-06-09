<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnTeamInjuriesMessage
{
    public function __construct(
        public readonly int    $teamId,
        public readonly ?array $importEntities = null,
    )
    {
    }
}
