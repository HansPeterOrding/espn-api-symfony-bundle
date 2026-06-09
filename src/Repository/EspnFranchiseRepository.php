<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnFranchise;
use HansPeterOrding\EspnApiClient\Dto\EspnFranchise as EspnFranchiseDto;

/**
 * @extends ServiceEntityRepository<EspnFranchise>
 */
class EspnFranchiseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnFranchise::class);
    }

    public function findByDtoOrCreateEntity(EspnFranchiseDto $dto): EspnFranchise
    {
        $entity = new EspnFranchise();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
