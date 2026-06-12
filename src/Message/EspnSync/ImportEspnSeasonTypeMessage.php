<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnSeasonTypeMessage
{
    public function __construct(
        public string $reference,
        public int    $seasonId,
        public bool   $isCurrent = false,
        public ?array $importEntities = null,
    )
    {
    }
}
