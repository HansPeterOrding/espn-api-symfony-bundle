<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use Doctrine\Common\Collections\ArrayCollection;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonTypeConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonTypeGroupConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeGroup;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnSeasonTypeGroupConverter $converter
 */
class EspnSeasonTypeGroupImporter extends AbstractImporter
{
    public function importForType(int $year, EspnSeasonType $type): iterable
    {
        foreach ($this->buildEntitiesForType($year, $type) as $group) {
            yield $group;
        }
    }

    private function buildEntitiesForType(int $year, EspnSeasonType $type): iterable
    {
        $espnSeasonTypeGroupReferences = $this->espnApiClient->season()->type()->group()->listRefs(
            $year,
            $type->getType()
        );

        foreach ($espnSeasonTypeGroupReferences as $espnSeasonTypeGroupReference) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $espnSeasonTypeGroupReference,
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_GROUP
            );

            $entity = $this->buildEntityFromReference($espnSeasonTypeGroupReference);

            if ($entity->getChildrenReference()) {
                foreach ($this->buildChildEntitiesForTypeGroup($year, $type->getType(), $urlParams->groupId) as $child) {
                    $entity->addOrReplaceChild($child);
                }
            }

            yield $entity;
        }
    }

    private function buildChildEntitiesForTypeGroup(int $year, int $typeId, int $groupId): iterable
    {
        $espnSeasonTypeChildGroupReferences = $this->espnApiClient->season()->type()->group()->listChildrenRefs(
            $year,
            $typeId,
            $groupId
        );

        foreach ($espnSeasonTypeChildGroupReferences as $espnSeasonTypeChildGroupReference) {
            yield $this->buildEntityFromReference($espnSeasonTypeChildGroupReference);
        }
    }

    private function buildEntityFromReference(string $seasonTypeGroupReference): EspnSeasonTypeGroup
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $seasonTypeGroupReference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_GROUP
        );

        $espnSeasonTypeGroup = $this->espnApiClient->season()->type()->group()->get(
            $urlParams->year,
            $urlParams->typeId,
            $urlParams->groupId
        );

        if (!$espnSeasonTypeGroup) {
            throw new ImportException(sprintf(
                'Season type %s not found for %s',
                $urlParams->typeId,
                $urlParams->year
            ));
        }

        return $this->converter->toEntity($espnSeasonTypeGroup);
    }
}
