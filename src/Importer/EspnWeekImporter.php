<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnWeekConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnWeek;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnWeekConverter $converter
 */
class EspnWeekImporter extends AbstractImporter
{
    public function buildEntityFromReference(string $reference, EspnSeasonType $espnSeasonType): EspnWeek
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_WEEK
        );

        if (null === $urlParams->year || null === $urlParams->typeId || null === $urlParams->weekNumber) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve year, typeId or weekNumber from week reference: %s',
                $reference
            ));
        }

        $espnWeekDto = $this->espnApiClient->seasons()->seasonTypes()->weeks()->get(
            $urlParams->year,
            $urlParams->typeId,
            $urlParams->weekNumber
        );

        if (!$espnWeekDto) {
            throw new ImportException(sprintf(
                'Week %d for type %d season %d not found',
                $urlParams->weekNumber,
                $urlParams->typeId,
                $urlParams->year
            ));
        }

        $espnWeek = $this->converter->toEntity($espnWeekDto, $espnSeasonType);
        $espnWeek->setSeasonType($espnSeasonType);

        return $espnWeek;
    }
}
