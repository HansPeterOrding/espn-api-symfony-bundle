<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitionStatusType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\CompetitionStatusStateEnum;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetitionStatusType as EspnCompetitionStatusTypeDto;

class EspnCompetitionStatusTypeConverter implements ConverterInterface
{
    public function toEntity(EspnCompetitionStatusTypeDto $espnCompetitionStatusTypeDto): EspnCompetitionStatusType
    {
        $espnCompetitionStatusType = new EspnCompetitionStatusType();

        $espnCompetitionStatusType->setEspnId($espnCompetitionStatusTypeDto->getId());
        $espnCompetitionStatusType->setName($espnCompetitionStatusTypeDto->getName());
        $espnCompetitionStatusType->setCompleted($espnCompetitionStatusTypeDto->getCompleted());
        $espnCompetitionStatusType->setDescription($espnCompetitionStatusTypeDto->getDescription());
        $espnCompetitionStatusType->setDetail($espnCompetitionStatusTypeDto->getDetail());
        $espnCompetitionStatusType->setShortDetail($espnCompetitionStatusTypeDto->getShortDetail());

        if (null !== $espnCompetitionStatusTypeDto->getState()) {
            $espnCompetitionStatusType->setState(
                CompetitionStatusStateEnum::tryFrom($espnCompetitionStatusTypeDto->getState())
            );
        }

        return $espnCompetitionStatusType;
    }
}
