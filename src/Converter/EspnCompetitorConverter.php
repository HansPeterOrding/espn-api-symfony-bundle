<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnCompetitor as EspnCompetitorDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnCompetitorHomeAwayEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnCompetitorTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitor as EspnCompetitorEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitorScore;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitorRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;

class EspnCompetitorConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnCompetitorRepository $espnCompetitorRepository,
        private readonly EspnTeamRepository $espnTeamRepository,
        private readonly EspnCompetitorScoreConverter $espnCompetitorScoreConverter
    )
    {
    }

    public function toEntity(EspnCompetition $competition, EspnCompetitorDto $espnCompetitorDto): EspnCompetitorEntity
    {
        $espnCompetitorEntity = $this->espnCompetitorRepository->findByDtoOrCreateEntity($competition, $espnCompetitorDto);

        $espnCompetitorEntity->setCompetitorId($espnCompetitorDto->getId());
        $espnCompetitorEntity->setType(EspnCompetitorTypeEnum::from($espnCompetitorDto->getType()));
        $espnCompetitorEntity->setSortOrder($espnCompetitorDto->getOrder());
        $espnCompetitorEntity->setHomeAway(EspnCompetitorHomeAwayEnum::from($espnCompetitorDto->getHomeAway()));
        $espnCompetitorEntity->setWinner($espnCompetitorDto->isWinner());

        $team = $this->espnTeamRepository->findOneBy([
            'teamId' => $espnCompetitorDto->getTeam()->getId(),
        ]);
        if(!$team) {
            // @todo: exception?
        }
        $espnCompetitorEntity->setTeam($team);

        $score = new EspnCompetitorScore();
        if($espnCompetitorDto->getScore()) {
            $score = $this->espnCompetitorScoreConverter->toEntity($espnCompetitorDto->getScore(), $espnCompetitorEntity->getScore());
        }
        $espnCompetitorEntity->setScore($score);

        return $espnCompetitorEntity;
    }
}
