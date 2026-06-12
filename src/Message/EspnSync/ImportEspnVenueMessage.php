<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnVenueMessage
{
    public function __construct(
        public string $reference,
        public ?array $importEntities = null,
    )
    {
    }
}
