<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnCompetitionStatusType as EspnCompetitionStatusTypeDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnCompetitionStatusTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitionStatusType as EspnCompetitionStatusTypeEntity;

class EspnCompetitionStatusTypeConverter implements ConverterInterface
{
    public function toEntity(EspnCompetitionStatusTypeDto $espnCompetitionStatusTypeDto, $espnCompetitionStatusTypeEntity = null): EspnCompetitionStatusTypeEntity
    {
        if (!$espnCompetitionStatusTypeEntity) {
            $espnCompetitionStatusTypeEntity = new EspnCompetitionStatusTypeEntity();
        }

        $espnCompetitionStatusTypeEntity->setType(EspnCompetitionStatusTypeEnum::from($espnCompetitionStatusTypeDto->getName()));
        $espnCompetitionStatusTypeEntity->setDetail($espnCompetitionStatusTypeDto->getDetail());
        $espnCompetitionStatusTypeEntity->setShortDetail($espnCompetitionStatusTypeDto->getShortDetail());

        return $espnCompetitionStatusTypeEntity;
    }
}
