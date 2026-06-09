<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnRecordMessage
{
    public function __construct(
        public readonly string $reference,
        public readonly ?array $importEntities = null,
    )
    {
    }
}
