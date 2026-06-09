<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnContractMessage
{
    public function __construct(
        public readonly string $reference,
        public readonly int $athleteId,
        public readonly ?array $importEntities = null,
    ) {
    }
}
