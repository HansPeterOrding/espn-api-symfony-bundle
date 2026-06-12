<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnOfficialMessage
{
    public function __construct(
        public string $reference,
        public int    $competitionId,
        public ?array $importEntities = null,
    )
    {
    }
}
