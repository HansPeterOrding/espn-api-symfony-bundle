<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClient;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientFactory;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

abstract class AbstractImporter {
    protected EspnApiClient $espnApiClient;

    public function __construct(
        protected readonly ConverterInterface     $converter,
        protected readonly EntityManagerInterface $entityManager
    )
    {
        $this->espnApiClient = (new EspnApiClientFactory())->getEspnApiClient();
    }
}
