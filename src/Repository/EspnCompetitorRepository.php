<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetitor as EspnCompetitorDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitor as EspnCompetitorEntity;

/**
 * @extends ServiceEntityRepository<EspnCompetitorEntity>
 */
class EspnCompetitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnCompetitorEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnCompetition $competition, EspnCompetitorDto $espnCompetitorDto): EspnCompetitorEntity
    {
        $espnCompetitor = new EspnCompetitorEntity();
        if(!$competition->getId()) {
            return $espnCompetitor;
        }

        if (null !== ($existingEntity = $this->findOneBy(
                $espnCompetitor->buildFindByCriteriaFromDto($competition, $espnCompetitorDto)
            ))) {
            $espnCompetitor = $existingEntity;
        }

        return $espnCompetitor;
    }
}
