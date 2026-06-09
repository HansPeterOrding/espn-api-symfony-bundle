<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitionType;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetitionType as EspnCompetitionTypeDto;

class EspnCompetitionTypeConverter implements ConverterInterface
{
    public function toEntity(EspnCompetitionTypeDto $espnCompetitionTypeDto): EspnCompetitionType
    {
        $espnCompetitionType = new EspnCompetitionType();

        $espnCompetitionType->setEspnId($espnCompetitionTypeDto->getId());
        $espnCompetitionType->setText($espnCompetitionTypeDto->getText());
        $espnCompetitionType->setAbbreviation($espnCompetitionTypeDto->getAbbreviation());
        $espnCompetitionType->setSlug($espnCompetitionTypeDto->getSlug());
        $espnCompetitionType->setType($espnCompetitionTypeDto->getType());

        return $espnCompetitionType;
    }
}
