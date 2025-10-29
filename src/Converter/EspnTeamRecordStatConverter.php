<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnTeamRecordStat as EspnTeamRecordStatDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeamRecordStat as EspnTeamRecordStatEntity;

class EspnTeamRecordStatConverter implements ConverterInterface
{
    public function toEntity(EspnTeamRecordStatDto $espnTeamRecordStatDto, $espnTeamRecordStatEntity = null): EspnTeamRecordStatEntity
    {
        if (!$espnTeamRecordStatEntity) {
            $espnTeamRecordStatEntity = new EspnTeamRecordStatEntity();
        }

        $espnTeamRecordStatEntity->setName($espnTeamRecordStatDto->getName());
        $espnTeamRecordStatEntity->setValue($espnTeamRecordStatDto->getValue());

        return $espnTeamRecordStatEntity;
    }
}
