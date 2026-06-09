<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use DateTimeImmutable;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnWeek;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnWeekRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnWeek as EspnWeekDto;

class EspnWeekConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnWeekRepository $espnWeekRepository,
    ) {
    }

    public function toEntity(EspnWeekDto $espnWeekDto, EspnSeasonType $espnSeasonType): EspnWeek
    {
        $espnWeek = $this->espnWeekRepository->findByDtoOrCreateEntity($espnWeekDto, $espnSeasonType);

        $espnWeek->setNumber($espnWeekDto->getNumber());
        $espnWeek->setText($espnWeekDto->getText());
        $espnWeek->setRankingsReference($espnWeekDto->getRankingsReference());
        $espnWeek->setEventsReference($espnWeekDto->getEventsReference());
        $espnWeek->setTalentpicksReference($espnWeekDto->getTalentpicksReference());
        $espnWeek->setQbrReference($espnWeekDto->getQbrReference());

        if (null !== $espnWeekDto->getStartDate()) {
            $espnWeek->setStartDate(new DateTimeImmutable($espnWeekDto->getStartDate()));
        }

        if (null !== $espnWeekDto->getEndDate()) {
            $espnWeek->setEndDate(new DateTimeImmutable($espnWeekDto->getEndDate()));
        }

        return $espnWeek;
    }
}
