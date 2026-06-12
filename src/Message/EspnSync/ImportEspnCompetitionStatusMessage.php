<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnCompetitionStatusMessage
{
    public function __construct(
        public string $reference,
        public ?array $importEntities = null,
    )
    {
    }
}
