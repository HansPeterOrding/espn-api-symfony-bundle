<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnScoreMessage
{
    public function __construct(
        public string $reference,
        public int    $competitorId,
        public ?array $importEntities = null,
    )
    {
    }
}
