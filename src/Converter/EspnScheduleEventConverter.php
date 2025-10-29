<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnScheduleEvent as EspnScheduleEventDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnSeasonTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnScheduleEvent as EspnScheduleEventEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnScheduleEventRepository;

class EspnScheduleEventConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnScheduleEventRepository $espnScheduleEventRepository,
        private readonly EspnWeekConverter $espnWeekConverter,
        private readonly EspnCompetitionConverter $espnCompetitionConverter,
    )
    {
    }

    public function toEntity(EspnSeason $season, EspnScheduleEventDto $espnScheduleEventDto): EspnScheduleEventEntity
    {
        $espnScheduleEventEntity = $this->espnScheduleEventRepository->findByDtoOrCreateEntity($espnScheduleEventDto);

        $espnScheduleEventEntity->setScheduleEventId($espnScheduleEventDto->getId());
        $espnScheduleEventEntity->setDate($espnScheduleEventDto->getDate());
        $espnScheduleEventEntity->setName($espnScheduleEventDto->getName());
        $espnScheduleEventEntity->setShortName($espnScheduleEventDto->getShortName());
        $espnScheduleEventEntity->setSeason($season);
        $espnScheduleEventEntity->setSeasonType(EspnSeasonTypeEnum::from($espnScheduleEventDto->getSeasonType()->getType()));

        $week = $this->espnWeekConverter->toEntity($espnScheduleEventDto->getWeek(), $espnScheduleEventEntity->getWeek());
        $espnScheduleEventEntity->setWeek($week);

        $espnScheduleEventEntity->setTimeValid($espnScheduleEventDto->isTimeValid());

        foreach($espnScheduleEventDto->getCompetitions() as $competition) {
            $competitionEntity = $this->espnCompetitionConverter->toEntity($competition);
            $espnScheduleEventEntity->addOrReplaceCompetition($competitionEntity);
        }

        return $espnScheduleEventEntity;
    }
}
