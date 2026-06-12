<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync;

readonly class ImportEspnPositionsMessage
{
    public function __construct(
        public ?array $importEntities = null,
    )
    {
    }
}
