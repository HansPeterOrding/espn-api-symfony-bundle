<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnInjuryType;
use HansPeterOrding\EspnApiClient\Dto\EspnInjuryType as EspnInjuryTypeDto;

class EspnInjuryTypeConverter implements ConverterInterface
{
    public function toEntity(EspnInjuryTypeDto $espnInjuryTypeDto): EspnInjuryType
    {
        $espnInjuryType = new EspnInjuryType();

        $espnInjuryType->setEspnId($espnInjuryTypeDto->getId());
        $espnInjuryType->setName($espnInjuryTypeDto->getName());
        $espnInjuryType->setDescription($espnInjuryTypeDto->getDescription());
        $espnInjuryType->setAbbreviation($espnInjuryTypeDto->getAbbreviation());

        return $espnInjuryType;
    }
}
