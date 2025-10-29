<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnSeason as EspnSeasonDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason as EspnSeasonEntity;

/**
 * @extends ServiceEntityRepository<EspnSeasonEntity>
 */
class EspnSeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnSeasonEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnSeasonDto $espnSeasonDto): EspnSeasonEntity
    {
        $espnSeason = new EspnSeasonEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnSeason->buildFindByCriteriaFromDto($espnSeasonDto)
            ))) {
            $espnSeason = $existingEntity;
        }

        return $espnSeason;
    }
}
