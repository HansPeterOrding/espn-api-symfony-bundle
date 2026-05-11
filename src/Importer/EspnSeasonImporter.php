<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;

/**
 * @property EspnSeasonConverter $converter
 */
class EspnSeasonImporter extends AbstractImporter
{
    public function import(int $year): EspnSeason
    {
        $espnSeason = $this->espnApiClient->season()->get($year);

        if(!$espnSeason) {
            throw new ImportException(sprintf('Season %s not found', $year));
        }

        $entity = $this->converter->toEntity($espnSeason);

        return $entity;
    }
}
