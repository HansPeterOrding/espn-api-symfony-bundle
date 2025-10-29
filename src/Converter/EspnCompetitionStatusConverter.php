<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnCompetitionStatus as EspnCompetitionStatusDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitionStatus as EspnCompetitionStatusEntity;

class EspnCompetitionStatusConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnCompetitionStatusTypeConverter $espnCompetitionStatusTypeConverter,
    )
    {
    }

    public function toEntity(EspnCompetitionStatusDto $espnCompetitionStatusDto, $espnCompetitionStatusEntity = null): EspnCompetitionStatusEntity
    {
        if (!$espnCompetitionStatusEntity) {
            $espnCompetitionStatusEntity = new EspnCompetitionStatusEntity();
        }

        $espnCompetitionStatusEntity->setClock($espnCompetitionStatusDto->getClock());
        $espnCompetitionStatusEntity->setDisplayClock($espnCompetitionStatusDto->getDisplayClock());
        $espnCompetitionStatusEntity->setPeriod($espnCompetitionStatusDto->getPeriod());

        $type = $this->espnCompetitionStatusTypeConverter->toEntity($espnCompetitionStatusDto->getType(), $espnCompetitionStatusEntity->getType());
        $espnCompetitionStatusEntity->setType($type);

        $espnCompetitionStatusEntity->setIsTBDFlex($espnCompetitionStatusDto->isTBDFlex());

        return $espnCompetitionStatusEntity;
    }
}
