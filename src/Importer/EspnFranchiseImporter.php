<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use Doctrine\Common\Collections\ArrayCollection;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnFranchiseConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnFranchise;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnFranchiseConverter $converter
 */
class EspnFranchiseImporter extends AbstractImporter
{
    public function import(EspnSeasonTeam $espnSeasonTeam): EspnFranchise
    {
        return $this->buildEntityFromReference($espnSeasonTeam->getFranchiseReference());
    }

    private function buildEntityFromReference(string $franchiseReference): EspnFranchise
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $franchiseReference,
            EspnUrlPatternResolver::URL_PATTERN_FRANCHISE
        );

        $espnFranchise = $this->espnApiClient->franchise()->get(
            $urlParams->franchiseId
        );

        if (!$espnFranchise) {
            throw new ImportException(sprintf(
                'Franchise %s not found',
                $urlParams->franchiseId
            ));
        }

        return $this->converter->toEntity($espnFranchise);
    }
}
