<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnCompetitorMessage
{
    public function __construct(
        public readonly string $reference,
        public readonly int $competitionId,
        public readonly ?array $importEntities = null,
    ) {
    }
}
