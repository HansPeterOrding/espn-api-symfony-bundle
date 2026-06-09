<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnAddress;
use HansPeterOrding\EspnApiClient\Dto\EspnAddress as EspnAddressDto;

class EspnAddressConverter implements ConverterInterface
{
    public function toEntity(EspnAddressDto $espnAddressDto): EspnAddress
    {
        $espnAddress = new EspnAddress();

        $espnAddress->setCity($espnAddressDto->getCity());
        $espnAddress->setState($espnAddressDto->getState());
        $espnAddress->setZipCode($espnAddressDto->getZipCode());
        $espnAddress->setCountry($espnAddressDto->getCountry());

        return $espnAddress;
    }
}
