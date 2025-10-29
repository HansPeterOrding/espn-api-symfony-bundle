<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnVenueConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenue;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;

/**
 * @property EspnVenueConverter $converter
 */
class EspnVenueImporter extends AbstractImporter
{
    public function import(string $venueId): EspnVenue
    {
        $espnVenue = $this->espnApiClient->venue()->get($venueId);

        if(!$espnVenue) {
            throw new ImportException(sprintf('Venue with venueId %s not found', $venueId));
        }

        $entity = $this->converter->toEntity($espnVenue);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @return EspnVenue[]
     */
    public function importAll(): array
    {
        $venueIds = $this->espnApiClient->venue()->listIds();

        $venues = [];
        foreach($venueIds as $venueId) {
            try {
                $venues[] = $this->import($venueId);
            } catch (\Throwable $e) {
                echo "Fehler bei ID ". $venueId;
                continue;
            }
        }

        return $venues;
    }
}
