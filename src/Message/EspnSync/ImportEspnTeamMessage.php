<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnTeamMessage
{
    public function __construct(
        public readonly string $reference,
        public readonly int $seasonId,
        public readonly ?array $importEntities = null,
    ) {
    }
}
