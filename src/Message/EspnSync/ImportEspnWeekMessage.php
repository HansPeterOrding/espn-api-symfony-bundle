<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnWeekMessage
{
    public function __construct(
        public readonly string $reference,
        public readonly int $seasonTypeId,
        public readonly ?array $importEntities = null,
    ) {
    }
}
