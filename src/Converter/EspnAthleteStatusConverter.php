<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnAthleteStatus;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\AthleteStatusTypeEnum;
use HansPeterOrding\EspnApiClient\Dto\EspnAthleteStatus as EspnAthleteStatusDto;

class EspnAthleteStatusConverter implements ConverterInterface
{
    public function toEntity(EspnAthleteStatusDto $espnAthleteStatusDto): EspnAthleteStatus
    {
        $espnAthleteStatus = new EspnAthleteStatus();

        $espnAthleteStatus->setEspnId($espnAthleteStatusDto->getId());
        $espnAthleteStatus->setName($espnAthleteStatusDto->getName());
        $espnAthleteStatus->setAbbreviation($espnAthleteStatusDto->getAbbreviation());

        if (null !== $espnAthleteStatusDto->getType()) {
            $espnAthleteStatus->setType(AthleteStatusTypeEnum::tryFrom($espnAthleteStatusDto->getType()));
        }

        return $espnAthleteStatus;
    }
}
