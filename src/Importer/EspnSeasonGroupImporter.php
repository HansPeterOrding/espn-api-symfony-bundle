<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonGroupConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonGroup;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnSeasonGroupConverter $converter
 */
class EspnSeasonGroupImporter extends AbstractImporter
{
    public function buildEntityFromReference(string $reference, ?EspnSeasonGroup $parentGroup = null): EspnSeasonGroup
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_GROUP
        );

        if (null === $urlParams->year || null === $urlParams->typeId || null === $urlParams->groupId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve year, typeId or groupId from season group reference: %s',
                $reference
            ));
                    }

        $espnSeasonGroupDto = $this->espnApiClient->seasons()->seasonTypes()->seasonGroups()->get(
            $urlParams->year,
            $urlParams->typeId,
            $urlParams->groupId
        );

        if (!$espnSeasonGroupDto) {
            throw new ImportException(sprintf(
                'SeasonGroup %d for type %d season %d not found',
                $urlParams->groupId,
                $urlParams->typeId,
                $urlParams->year
            ));
        }

        $espnSeasonGroup = $this->converter->toEntity($espnSeasonGroupDto);

        // Always set parent — null explicitly clears stale parent from previous imports
        $espnSeasonGroup->setParent($parentGroup);

        return $espnSeasonGroup;
    }
}
