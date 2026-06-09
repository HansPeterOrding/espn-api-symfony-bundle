<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonGroup;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonGroup as EspnSeasonGroupDto;

/**
 * @extends ServiceEntityRepository<EspnSeasonGroup>
 */
class EspnSeasonGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnSeasonGroup::class);
    }

    public function findByDtoOrCreateEntity(EspnSeasonGroupDto $dto): EspnSeasonGroup
    {
        $entity = new EspnSeasonGroup();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
