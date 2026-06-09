<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiClient\Dto\EspnTeam as EspnTeamDto;

/**
 * @extends ServiceEntityRepository<EspnTeam>
 */
class EspnTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnTeam::class);
    }

    public function findByDtoOrCreateEntity(EspnTeamDto $dto): EspnTeam
    {
        $entity = new EspnTeam();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
