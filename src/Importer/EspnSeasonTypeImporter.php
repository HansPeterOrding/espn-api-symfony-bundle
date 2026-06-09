<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonTypeConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnSeasonTypeConverter $converter
 */
class EspnSeasonTypeImporter extends AbstractImporter
{
    public function __construct(
        \HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface $espnApiClient,
        \HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface $converter,
        private readonly EspnSeasonTypeRepository $espnSeasonTypeRepository,
    ) {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference, EspnSeason $espnSeason, bool $isCurrent = false): EspnSeasonType
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE
        );

        if (null === $urlParams->year || null === $urlParams->typeId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve year or typeId from season type reference: %s',
                $reference
            ));
                    }

        $espnSeasonTypeDto = $this->espnApiClient->seasons()->seasonTypes()->get(
            $urlParams->year,
            $urlParams->typeId
        );

        if (!$espnSeasonTypeDto) {
            throw new ImportException(sprintf(
                'SeasonType %d for season %d not found',
                $urlParams->typeId,
                $urlParams->year
            ));
        }

        $espnSeasonType = $this->converter->toEntity($espnSeasonTypeDto, $espnSeason);
        $espnSeasonType->setSeason($espnSeason);

        if ($isCurrent) {
            $this->espnSeasonTypeRepository->resetCurrentForSeason($espnSeason);
            $espnSeasonType->setIsCurrent(true);
        } else {
            $espnSeasonType->setIsCurrent(false);
        }

        return $espnSeasonType;
    }
}
