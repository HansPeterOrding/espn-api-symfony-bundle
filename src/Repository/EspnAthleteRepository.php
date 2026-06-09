<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnAthlete;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiClient\Dto\EspnAthlete as EspnAthleteDto;

/**
 * @extends ServiceEntityRepository<EspnAthlete>
 */
class EspnAthleteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnAthlete::class);
    }

    public function findByDtoOrCreateEntity(EspnAthleteDto $dto, EspnSeason $season): EspnAthlete
    {
        $entity = new EspnAthlete();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto, $season)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
