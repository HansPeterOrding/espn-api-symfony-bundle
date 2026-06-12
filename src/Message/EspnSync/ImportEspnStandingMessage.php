<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnStandingMessage
{
    public function __construct(
        public string $reference,
        public int    $seasonGroupId,
        public int    $seasonId,
        public ?array $importEntities = null,
    )
    {
    }
}
