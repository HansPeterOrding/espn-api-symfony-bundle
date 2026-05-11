<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeTeamRecord as EspnSeasonTypeTeamRecordDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeTeamRecord as EspnSeasonTypeTeamRecordEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeTeamRecordRepository;

class EspnSeasonTypeTeamRecordConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnSeasonTypeTeamRecordRepository $espnSeasonTypeTeamRecordRepository,
        private readonly EspnSeasonTypeTeamRecordStatConverter $statConverter,
    )
    {
    }

    public function toEntity(EspnSeasonType $espnSeasonType, EspnSeasonTeam $espnSeasonTeam, EspnSeasonTypeTeamRecordDto $espnSeasonTypeTeamRecordDto): EspnSeasonTypeTeamRecordEntity
    {
        $espnSeasonTypeTeamRecordEntity = $this->espnSeasonTypeTeamRecordRepository->findByDtoOrCreateEntity($espnSeasonType, $espnSeasonTeam, $espnSeasonTypeTeamRecordDto);

        $espnSeasonTypeTeamRecordEntity->setEspnId($espnSeasonTypeTeamRecordDto->getId());
        $espnSeasonTypeTeamRecordEntity->setName($espnSeasonTypeTeamRecordDto->getName());
        $espnSeasonTypeTeamRecordEntity->setAbbreviation($espnSeasonTypeTeamRecordDto->getAbbreviation());
        $espnSeasonTypeTeamRecordEntity->setType($espnSeasonTypeTeamRecordDto->getType());
        $espnSeasonTypeTeamRecordEntity->setSummary($espnSeasonTypeTeamRecordDto->getSummary());
        $espnSeasonTypeTeamRecordEntity->setDisplayValue($espnSeasonTypeTeamRecordDto->getDisplayValue());
        $espnSeasonTypeTeamRecordEntity->setValue($espnSeasonTypeTeamRecordDto->getValue());

        foreach ($espnSeasonTypeTeamRecordDto->getStats() as $stat) {
            $statEntity = $this->statConverter->toEntity($espnSeasonTypeTeamRecordEntity, $stat);
            $espnSeasonTypeTeamRecordEntity->addOrReplaceStat($statEntity);
        }

        return $espnSeasonTypeTeamRecordEntity;
    }
}
