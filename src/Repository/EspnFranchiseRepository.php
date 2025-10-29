<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnFranchise as EspnFranchiseDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnFranchise as EspnFranchiseEntity;

/**
 * @extends ServiceEntityRepository<EspnFranchiseEntity>
 */
class EspnFranchiseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnFranchiseEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnFranchiseDto $espnFranchiseDto): EspnFranchiseEntity
    {
        $espnFranchise = new EspnFranchiseEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnFranchise->buildFindByCriteriaFromDto($espnFranchiseDto)
            ))) {
            $espnFranchise = $existingEntity;
        }

        return $espnFranchise;
    }
}
