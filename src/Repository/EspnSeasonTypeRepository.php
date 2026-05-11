<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonType as EspnSeasonTypeDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType as EspnSeasonTypeEntity;

/**
 * @extends ServiceEntityRepository<EspnSeasonTypeEntity>
 */
class EspnSeasonTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnSeasonTypeEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnSeasonTypeDto $espnSeasonTypeDto): EspnSeasonTypeEntity
    {
        $espnSeasonType = new EspnSeasonTypeEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnSeasonType->buildFindByCriteriaFromDto($espnSeasonTypeDto)
            ))) {
            $espnSeasonType = $existingEntity;
        }

        return $espnSeasonType;
    }
}
