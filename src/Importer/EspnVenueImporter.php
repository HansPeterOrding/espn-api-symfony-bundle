<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use Doctrine\Common\Collections\ArrayCollection;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnVenueConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenue;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnVenueConverter $converter
 */
class EspnVenueImporter extends AbstractImporter
{
    public function importForTeam(EspnSeasonTeam $espnSeasonTeam): EspnVenue
    {
        return $this->buildEntityFromReference($espnSeasonTeam->getVenueReference());
    }

    private function buildEntityFromReference(string $venueReference): EspnVenue
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $venueReference,
            EspnUrlPatternResolver::URL_PATTERN_VENUE
        );

        $espnVenue = $this->espnApiClient->venue()->get(
            $urlParams->venueId
        );

        if (!$espnVenue) {
            throw new ImportException(sprintf(
                'Venue %s not found',
                $urlParams->venueId
            ));
        }

        return $this->converter->toEntity($espnVenue);
    }
}
