<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnVenueAddress as EspnVenueAddressDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenueAddressEmbeddable as EspnVenueAddressEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnVenueAddressRepository;

class EspnVenueAddressConverter implements ConverterInterface
{
    public function toEntity(EspnVenueAddressDto $espnVenueAddressDto): EspnVenueAddressEntity
    {
        $espnVenueAddressEntity = new EspnVenueAddressEntity();

        $espnVenueAddressEntity->setCity($espnVenueAddressDto->getCity());
        $espnVenueAddressEntity->setState($espnVenueAddressDto->getState());
        $espnVenueAddressEntity->setZipCode($espnVenueAddressDto->getZipCode());
        $espnVenueAddressEntity->setCountry($espnVenueAddressDto->getCountry());

        return $espnVenueAddressEntity;
    }
}
