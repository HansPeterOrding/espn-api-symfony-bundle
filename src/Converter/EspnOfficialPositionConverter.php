<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnOfficialPosition;
use HansPeterOrding\EspnApiClient\Dto\EspnOfficialPosition as EspnOfficialPositionDto;

class EspnOfficialPositionConverter implements ConverterInterface
{
    public function toEntity(EspnOfficialPositionDto $espnOfficialPositionDto): EspnOfficialPosition
    {
        $espnOfficialPosition = new EspnOfficialPosition();

        $espnOfficialPosition->setEspnId($espnOfficialPositionDto->getId());
        $espnOfficialPosition->setName($espnOfficialPositionDto->getName());
        $espnOfficialPosition->setDisplayName($espnOfficialPositionDto->getDisplayName());

        return $espnOfficialPosition;
    }
}
