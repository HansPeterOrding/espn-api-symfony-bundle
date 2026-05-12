<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnVenue as EspnVenueDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenue as EspnVenueEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnVenueRepository;

class EspnVenueConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnVenueRepository $espnVenueRepository,
        private readonly EspnVenueAddressConverter $espnVenueAddressConverter,
    )
    {
    }

    public function toEntity(EspnVenueDto $espnVenueDto): EspnVenueEntity
    {
        $espnVenueEntity = $this->espnVenueRepository->findByDtoOrCreateEntity($espnVenueDto);

        $espnVenueEntity->setEspnId($espnVenueDto->getId());
        $espnVenueEntity->setGuid($espnVenueDto->getGuid());
        $espnVenueEntity->setFullName($espnVenueDto->getFullName());
        $espnVenueEntity->setAddress(
            $this->espnVenueAddressConverter->toEntity($espnVenueDto->getAddress())
        );
        $espnVenueEntity->setGrass($espnVenueDto->getGrass());
        $espnVenueEntity->setIndoor($espnVenueDto->getIndoor());

        return $espnVenueEntity;
    }
}
