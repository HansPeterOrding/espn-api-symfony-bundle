<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnSeasonConverter $converter
 */
class EspnSeasonImporter extends AbstractImporter
{
    public function buildEntityFromReference(string $reference): EspnSeason
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON
        );

        if (null === $urlParams->year) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve year from season reference: %s',
                $reference
            ));
        }

        $espnSeasonDto = $this->espnApiClient->seasons()->get($urlParams->year);

        if (!$espnSeasonDto) {
            throw new ImportException(sprintf(
                'Season %d not found',
                $urlParams->year
            ));
        }

        return $this->espnSeasonConverter->toEntity($espnSeasonDto);
    }
}
