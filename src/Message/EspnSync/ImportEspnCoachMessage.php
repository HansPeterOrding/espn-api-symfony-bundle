<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnCoachMessage
{
    public function __construct(
        public string $reference,
        public int    $seasonId,
        public ?array $importEntities = null,
    )
    {
    }
}
