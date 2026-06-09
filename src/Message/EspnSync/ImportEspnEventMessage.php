<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnEventMessage
{
    public function __construct(
        public readonly string $reference,
        public readonly int $seasonId,
        public readonly int $seasonTypeId,
        public readonly int $weekId,
        public readonly ?array $importEntities = null,
    ) {
    }
}
