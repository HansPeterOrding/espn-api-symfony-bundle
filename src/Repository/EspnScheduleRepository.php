<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnSchedule as EspnScheduleDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSchedule as EspnScheduleEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTeam;

/**
 * @extends ServiceEntityRepository<EspnScheduleEntity>
 */
class EspnScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnScheduleEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnSeasonTeam $team, EspnScheduleDto $espnScheduleDto): EspnScheduleEntity
    {
        $espnSchedule = new EspnScheduleEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnSchedule->buildFindByCriteriaFromDto($team)
            ))) {
            $espnSchedule = $existingEntity;
        }

        return $espnSchedule;
    }
}
