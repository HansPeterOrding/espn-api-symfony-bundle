<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeWeek as EspnSeasonTypeWeekDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeWeek as EspnSeasonTypeWeekEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeWeekRepository;

class EspnSeasonTypeWeekConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnSeasonTypeWeekRepository $espnSeasonTypeWeekRepository,
    )
    {
    }

    public function toEntity(EspnSeasonType $espnSeasonType, EspnSeasonTypeWeekDto $espnSeasonTypeWeekDto): EspnSeasonTypeWeekEntity
    {
        $espnSeasonTypeWeekEntity = $this->espnSeasonTypeWeekRepository->findByDtoOrCreateEntity($espnSeasonType, $espnSeasonTypeWeekDto);

        $espnSeasonTypeWeekEntity->setNumber($espnSeasonTypeWeekDto->getNumber());
        $espnSeasonTypeWeekEntity->setStartDate($espnSeasonTypeWeekDto->getStartDate());
        $espnSeasonTypeWeekEntity->setEndDate($espnSeasonTypeWeekDto->getEndDate());
        $espnSeasonTypeWeekEntity->setText($espnSeasonTypeWeekDto->getText());

        return $espnSeasonTypeWeekEntity;
    }
}
