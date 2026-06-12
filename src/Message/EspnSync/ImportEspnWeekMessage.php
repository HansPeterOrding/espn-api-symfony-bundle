<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnWeekMessage
{
    public function __construct(
        public string $reference,
        public int    $seasonTypeId,
        public ?array $importEntities = null,
    )
    {
    }
}
