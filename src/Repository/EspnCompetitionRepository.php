<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetition as EspnCompetitionDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition as EspnCompetitionEntity;

/**
 * @extends ServiceEntityRepository<EspnCompetitionEntity>
 */
class EspnCompetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnCompetitionEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnCompetitionDto $espnCompetitionDto): EspnCompetitionEntity
    {
        $espnCompetition = new EspnCompetitionEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnCompetition->buildFindByCriteriaFromDto($espnCompetitionDto)
            ))) {
            $espnCompetition = $existingEntity;
        }

        return $espnCompetition;
    }
}
