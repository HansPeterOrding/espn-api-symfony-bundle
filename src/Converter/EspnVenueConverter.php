<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnVenue as EspnVenueDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenue as EspnVenueEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenueAddress;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnVenueRepository;

class EspnVenueConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnVenueRepository $espnVenueRepository,
        private readonly EspnVenueAddressConverter $venueAddressConverter
    )
    {
    }

    public function toEntity(EspnVenueDto $espnVenueDto): EspnVenueEntity
    {
        $espnVenueEntity = $this->espnVenueRepository->findByDtoOrCreateEntity($espnVenueDto);

        $espnVenueEntity->setVenueId($espnVenueDto->getId());
        $espnVenueEntity->setGuid($espnVenueDto->getGuid());
        $espnVenueEntity->setFullName($espnVenueDto->getFullName());
        $espnVenueEntity->setGrass($espnVenueDto->getGrass());
        $espnVenueEntity->setIndoor($espnVenueDto->getIndoor());

        $venueAddress = new EspnVenueAddress();
        if($espnVenueDto->getAddress()) {
            $venueAddress = $this->venueAddressConverter->toEntity($espnVenueDto->getAddress(), $espnVenueEntity->getAddress());
        }
        $espnVenueEntity->setAddress($venueAddress);

        return $espnVenueEntity;
    }
}
