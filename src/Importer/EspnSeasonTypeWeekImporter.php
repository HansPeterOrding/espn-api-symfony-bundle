<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use Doctrine\Common\Collections\ArrayCollection;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonTypeConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonTypeWeekConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeWeek;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnSeasonTypeWeekConverter $converter
 */
class EspnSeasonTypeWeekImporter extends AbstractImporter
{
    public function importForType(int $year, EspnSeasonType $type): iterable
    {
        $espnSeasonTypeWeekReferences = $this->espnApiClient->season()->type()->week()->listRefs(
            $year,
            $type->getType()
        );

        foreach ($espnSeasonTypeWeekReferences as $espnSeasonTypeWeekReference) {
            yield $this->buildEntityFromReference($type, $espnSeasonTypeWeekReference);
        }
    }

    private function buildEntityFromReference(EspnSeasonType $espnSeasonType, string $seasonTypeWeekReference): EspnSeasonTypeWeek
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $seasonTypeWeekReference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_WEEK
        );

        $espnSeasonTypeWeek = $this->espnApiClient->season()->type()->week()->get(
            $urlParams->year,
            $urlParams->typeId,
            $urlParams->weekNumber
        );

        if (!$espnSeasonTypeWeek) {
            throw new ImportException(sprintf(
                'Week %s not found for season type %s and season %s',
                $urlParams->weekNumber,
                $urlParams->typeId,
                $urlParams->year
            ));
        }

        return $this->converter->toEntity($espnSeasonType, $espnSeasonTypeWeek);
    }
}
