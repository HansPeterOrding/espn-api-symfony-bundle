<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenue;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnVenueRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnVenue as EspnVenueDto;

readonly class EspnVenueConverter implements ConverterInterface
{
    public function __construct(
        private EspnVenueRepository  $espnVenueRepository,
        private EspnAddressConverter $espnAddressConverter,
    )
    {
    }

    public function toEntity(EspnVenueDto $espnVenueDto): EspnVenue
    {
        $espnVenue = $this->espnVenueRepository->findByDtoOrCreateEntity($espnVenueDto);

        $espnVenue->setEspnId($espnVenueDto->getId());
        $espnVenue->setGuid($espnVenueDto->getGuid());
        $espnVenue->setFullName($espnVenueDto->getFullName());
        $espnVenue->setGrass($espnVenueDto->getGrass());
        $espnVenue->setIndoor($espnVenueDto->getIndoor());

        if (null !== $espnVenueDto->getAddress()) {
            $espnVenue->setAddress($this->espnAddressConverter->toEntity($espnVenueDto->getAddress()));
        }

        return $espnVenue;
    }
}
