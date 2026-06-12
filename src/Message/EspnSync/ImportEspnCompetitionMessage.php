<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnCompetitionMessage
{
    public function __construct(
        public string $reference,
        public int    $eventId,
        public ?array $importEntities = null,
    )
    {
    }
}
