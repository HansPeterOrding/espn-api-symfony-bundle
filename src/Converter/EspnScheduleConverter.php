<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnSchedule as EspnScheduleDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnScheduleStatusEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSchedule as EspnScheduleEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnScheduleRepository;

class EspnScheduleConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnScheduleRepository $espnScheduleRepository,
        private readonly EspnSeasonConverter $espnSeasonConverter,
        private readonly EspnScheduleEventConverter $espnScheduleEventConverter,
    )
    {
    }

    public function toEntity(EspnTeam $espnTeam, EspnScheduleDto $espnScheduleDto): EspnScheduleEntity
    {
        $espnScheduleEntity = $this->espnScheduleRepository->findByDtoOrCreateEntity($espnTeam, $espnScheduleDto);

        $espnScheduleEntity->setTimestamp($espnScheduleDto->getTimestamp());
        $espnScheduleEntity->setStatus(EspnScheduleStatusEnum::from($espnScheduleDto->getStatus()));
        $espnScheduleEntity->setByeWeek($espnScheduleDto->getByeWeek());

        $season = $this->espnSeasonConverter->toEntity($espnScheduleDto->getSeason());
        $espnScheduleEntity->setSeason($season);

        $espnScheduleEntity->setTeam($espnTeam);

        foreach($espnScheduleDto->getEvents() as $event) {
            $eventEntity = $this->espnScheduleEventConverter->toEntity($season, $event);
            $espnScheduleEntity->addOrReplaceEvent($eventEntity);
        }

        return $espnScheduleEntity;
    }
}
