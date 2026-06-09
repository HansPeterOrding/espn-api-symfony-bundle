<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnScoreMessage
{
    public function __construct(
        public readonly string $reference,
        public readonly int $competitorId,
        public readonly ?array $importEntities = null,
    ) {
    }
}
