<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeTeamRecordStat as EspnSeasonTypeTeamRecordStatDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeTeamRecord;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeTeamRecordStat as EspnSeasonTypeTeamRecordStatEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeTeamRecordStatRepository;

class EspnSeasonTypeTeamRecordStatConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnSeasonTypeTeamRecordStatRepository $espnSeasonTypeTeamRecordStatRepository,
    )
    {
    }

    public function toEntity(EspnSeasonTypeTeamRecord $espnSeasonTypeTeamRecord, EspnSeasonTypeTeamRecordStatDto $espnSeasonTypeTeamRecordStatDto): EspnSeasonTypeTeamRecordStatEntity
    {
        $espnSeasonTypeTeamRecordStatEntity = $this->espnSeasonTypeTeamRecordStatRepository->findByDtoOrCreateEntity($espnSeasonTypeTeamRecord, $espnSeasonTypeTeamRecordStatDto);

        $espnSeasonTypeTeamRecordStatEntity->setName($espnSeasonTypeTeamRecordStatDto->getName());
        $espnSeasonTypeTeamRecordStatEntity->setDisplayName($espnSeasonTypeTeamRecordStatDto->getDisplayName());
        $espnSeasonTypeTeamRecordStatEntity->setShortDisplayName($espnSeasonTypeTeamRecordStatDto->getShortDisplayName());
        $espnSeasonTypeTeamRecordStatEntity->setDescription($espnSeasonTypeTeamRecordStatDto->getDescription());
        $espnSeasonTypeTeamRecordStatEntity->setAbbreviation($espnSeasonTypeTeamRecordStatDto->getAbbreviation());
        $espnSeasonTypeTeamRecordStatEntity->setType($espnSeasonTypeTeamRecordStatDto->getType());
        $espnSeasonTypeTeamRecordStatEntity->setValue($espnSeasonTypeTeamRecordStatDto->getValue());
        $espnSeasonTypeTeamRecordStatEntity->setDisplayValue($espnSeasonTypeTeamRecordStatDto->getDisplayValue());

        return $espnSeasonTypeTeamRecordStatEntity;
    }
}
