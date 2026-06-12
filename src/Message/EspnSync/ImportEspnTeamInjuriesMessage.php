<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnTeamInjuriesMessage
{
    public function __construct(
        public int    $teamId,
        public ?array $importEntities = null,
    )
    {
    }
}
