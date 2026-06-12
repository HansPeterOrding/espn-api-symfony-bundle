<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnSeasonGroupMessage
{
    public function __construct(
        public string $reference,
        public int    $seasonId,
        public ?int   $parentGroupId = null,
        public ?array $importEntities = null,
    )
    {
    }
}
