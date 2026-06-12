<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnEventMessage
{
    public function __construct(
        public string $reference,
        public int    $seasonId,
        public int    $seasonTypeId,
        public int    $weekId,
        public ?array $importEntities = null,
    )
    {
    }
}
