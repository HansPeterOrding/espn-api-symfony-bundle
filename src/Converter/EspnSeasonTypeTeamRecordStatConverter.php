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

        $espnSeasonTypeTeamRecordStatEntity->setEspnId($espnSeasonTypeTeamRecordStatDto->getId());
        $espnSeasonTypeTeamRecordStatEntity->setName($espnSeasonTypeTeamRecordStatDto->getName());
        $espnSeasonTypeTeamRecordStatEntity->setAbbreviation($espnSeasonTypeTeamRecordStatDto->getAbbreviation());
        $espnSeasonTypeTeamRecordStatEntity->setType($espnSeasonTypeTeamRecordStatDto->getType());
        $espnSeasonTypeTeamRecordStatEntity->setSummary($espnSeasonTypeTeamRecordStatDto->getSummary());
        $espnSeasonTypeTeamRecordStatEntity->setDisplayValue($espnSeasonTypeTeamRecordStatDto->getDisplayValue());
        $espnSeasonTypeTeamRecordStatEntity->setValue($espnSeasonTypeTeamRecordStatDto->getValue());

        $espnSeasonTypeTeamRecordStatEntity->removeAllStats();
        foreach ($espnSeasonTypeTeamRecordStatDto->getStats() as $stat) {
            $statEntity = $this->statConverter->toEntity($stat);
            $espnSeasonTypeTeamRecordStatEntity->addStat($statEntity);
        }

        return $espnSeasonTypeTeamRecordStatEntity;
    }
}
