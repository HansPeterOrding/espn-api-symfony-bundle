<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use Doctrine\Common\Collections\ArrayCollection;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonTypeConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnSeasonTypeConverter $converter
 */
class EspnSeasonTypeImporter extends AbstractImporter
{
    public function import(EspnSeason $espnSeason): EspnSeasonType
    {
        return $this->buildEntityFromReference($espnSeason->getTypeReference());
    }

    public function importAll(EspnSeason $espnSeason): iterable
    {
        $espnSeasonTypeReferences = $this->espnApiClient->season()->type()->listRefs(
            $espnSeason->getYear()
        );

        foreach ($espnSeasonTypeReferences as $espnSeasonTypeReference) {
            yield $this->buildEntityFromReference($espnSeasonTypeReference);
        }
    }

    private function buildEntityFromReference(string $seasonTypeReference): EspnSeasonType
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $seasonTypeReference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE
        );

        $espnSeasonType = $this->espnApiClient->season()->type()->get(
            $urlParams->year,
            $urlParams->typeId
        );

        if (!$espnSeasonType) {
            throw new ImportException(sprintf(
                'Season type %s not found for %s',
                $urlParams->typeId,
                $urlParams->year
            ));
        }

        return $this->converter->toEntity($espnSeasonType);
    }
}
