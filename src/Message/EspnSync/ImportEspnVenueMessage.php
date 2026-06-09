<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnVenueMessage
{
    public function __construct(
        public readonly string $reference,
        public readonly ?array $importEntities = null,
    ) {
    }
}
