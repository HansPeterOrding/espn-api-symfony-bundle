<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnSeasonGroupMessage
{
    public function __construct(
        public readonly string $reference,
        public readonly int $seasonId,
        public readonly ?int $parentGroupId = null,
        public readonly ?array $importEntities = null,
    ) {
    }
}
