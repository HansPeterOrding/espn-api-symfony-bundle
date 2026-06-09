<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCoach;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiClient\Dto\EspnCoach as EspnCoachDto;

/**
 * @extends ServiceEntityRepository<EspnCoach>
 */
class EspnCoachRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnCoach::class);
    }

    public function findByDtoOrCreateEntity(EspnCoachDto $dto, EspnSeason $season): EspnCoach
    {
        $entity = new EspnCoach();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto, $season)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
