<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonConverter;

abstract class AbstractImporter
{
    public function __construct(
        protected readonly EspnApiClientInterface $espnApiClient,
        protected readonly ConverterInterface     $converter,
    )
    {
    }
}
