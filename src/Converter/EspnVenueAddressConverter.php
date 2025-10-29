<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnVenueAddress as EspnVenueAddressDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenueAddress as EspnVenueAddressEntity;

class EspnVenueAddressConverter implements ConverterInterface
{
    public function toEntity(EspnVenueAddressDto $espnVenueAddressDto, $espnVenueAddressEntity = null): EspnVenueAddressEntity
    {
        if (!$espnVenueAddressEntity) {
            $espnVenueAddressEntity = new EspnVenueAddressEntity();
        }

        $espnVenueAddressEntity->setCity($espnVenueAddressDto->getCity());
        $espnVenueAddressEntity->setState($espnVenueAddressDto->getState());
        $espnVenueAddressEntity->setZipCode($espnVenueAddressDto->getZipCode());
        $espnVenueAddressEntity->setCity($espnVenueAddressDto->getCity());

        return $espnVenueAddressEntity;
    }
}
