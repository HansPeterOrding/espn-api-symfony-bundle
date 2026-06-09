<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

class ImportEspnPositionsMessage
{
    public function __construct(
        public readonly ?array $importEntities = null,
    ) {
    }
}
