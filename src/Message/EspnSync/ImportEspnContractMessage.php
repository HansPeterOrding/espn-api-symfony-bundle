<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnContractMessage
{
    public function __construct(
        public string $reference,
        public int    $athleteId,
        public ?array $importEntities = null,
    )
    {
    }
}
