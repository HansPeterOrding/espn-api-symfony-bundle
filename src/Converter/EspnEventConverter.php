<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use DateTimeImmutable;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnEvent;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnEventRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnEvent as EspnEventDto;

class EspnEventConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnEventRepository $espnEventRepository,
    ) {
    }

    public function toEntity(EspnEventDto $espnEventDto): EspnEvent
    {
        $espnEvent = $this->espnEventRepository->findByDtoOrCreateEntity($espnEventDto);

        $espnEvent->setEspnId($espnEventDto->getId());
        $espnEvent->setUid($espnEventDto->getUid());
        $espnEvent->setName($espnEventDto->getName());
        $espnEvent->setShortName($espnEventDto->getShortName());
        $espnEvent->setTimeValid($espnEventDto->getTimeValid());
        $espnEvent->setLeagueReference($espnEventDto->getLeagueReference());

        if (null !== $espnEventDto->getDate()) {
            $espnEvent->setDate(new DateTimeImmutable($espnEventDto->getDate()));
        }

        // season, seasonType, week entity relations connected in the importer

        return $espnEvent;
    }
}
