<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnCompetitionMessage
{
    public function __construct(
        public readonly string $reference,
        public readonly int $eventId,
        public readonly ?array $importEntities = null,
    ) {
    }
}
