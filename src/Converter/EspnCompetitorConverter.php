<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitor;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\CompetitorHomeAwayEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\CompetitorTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitorRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetitor as EspnCompetitorDto;

class EspnCompetitorConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnCompetitorRepository $espnCompetitorRepository,
    )
    {
    }

    public function toEntity(EspnCompetitorDto $espnCompetitorDto, EspnCompetition $espnCompetition): EspnCompetitor
    {
        $espnCompetitor = $this->espnCompetitorRepository->findByDtoOrCreateEntity(
            $espnCompetitorDto,
            $espnCompetition
        );

        $espnCompetitor->setEspnId($espnCompetitorDto->getId());
        $espnCompetitor->setUid($espnCompetitorDto->getUid());
        $espnCompetitor->setDisplayOrder($espnCompetitorDto->getOrder());
        $espnCompetitor->setWinner($espnCompetitorDto->getWinner());
        $espnCompetitor->setScoreReference($espnCompetitorDto->getScoreReference());
        $espnCompetitor->setLinescoresReference($espnCompetitorDto->getLinescoresReference());
        $espnCompetitor->setRosterReference($espnCompetitorDto->getRosterReference());
        $espnCompetitor->setStatisticsReference($espnCompetitorDto->getStatisticsReference());
        $espnCompetitor->setLeadersReference($espnCompetitorDto->getLeadersReference());
        $espnCompetitor->setRecordReference($espnCompetitorDto->getRecordReference());

        if (null !== $espnCompetitorDto->getType()) {
            $espnCompetitor->setType(CompetitorTypeEnum::tryFrom($espnCompetitorDto->getType()));
        }

        if (null !== $espnCompetitorDto->getHomeAway()) {
            $espnCompetitor->setHomeAway(CompetitorHomeAwayEnum::tryFrom($espnCompetitorDto->getHomeAway()));
        }

        // team entity is connected in the importer
        $espnCompetitor->setCompetition($espnCompetition);

        return $espnCompetitor;
    }
}
