<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitionStatus;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitionStatusRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetitionStatus as EspnCompetitionStatusDto;

class EspnCompetitionStatusConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnCompetitionStatusRepository    $espnCompetitionStatusRepository,
        private readonly EspnCompetitionStatusTypeConverter $espnCompetitionStatusTypeConverter,
    )
    {
    }

    public function toEntity(
        EspnCompetitionStatusDto $espnCompetitionStatusDto,
        EspnCompetition          $espnCompetition
    ): EspnCompetitionStatus
    {
        $espnCompetitionStatus = $this->espnCompetitionStatusRepository
            ->findByCompetitionOrCreateEntity($espnCompetition);

        $espnCompetitionStatus->setClock($espnCompetitionStatusDto->getClock());
        $espnCompetitionStatus->setDisplayClock($espnCompetitionStatusDto->getDisplayClock());
        $espnCompetitionStatus->setPeriod($espnCompetitionStatusDto->getPeriod());

        if (null !== $espnCompetitionStatusDto->getType()) {
            $espnCompetitionStatus->setType(
                $this->espnCompetitionStatusTypeConverter->toEntity($espnCompetitionStatusDto->getType())
            );
        }

        // competition connected in importer

        return $espnCompetitionStatus;
    }
}
