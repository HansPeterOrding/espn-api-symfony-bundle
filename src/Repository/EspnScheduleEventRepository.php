<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnScheduleEvent as EspnScheduleEventDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnScheduleEvent as EspnScheduleEventEntity;

/**
 * @extends ServiceEntityRepository<EspnScheduleEventEntity>
 */
class EspnScheduleEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnScheduleEventEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnScheduleEventDto $espnScheduleEventDto): EspnScheduleEventEntity
    {
        $espnScheduleEvent = new EspnScheduleEventEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnScheduleEvent->buildFindByCriteriaFromDto($espnScheduleEventDto)
            ))) {
            $espnScheduleEvent = $existingEntity;
        }

        return $espnScheduleEvent;
    }
}
