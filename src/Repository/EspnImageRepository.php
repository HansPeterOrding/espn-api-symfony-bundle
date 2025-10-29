<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnImage as EspnImageDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnImage as EspnImageEntity;

/**
 * @extends ServiceEntityRepository<EspnImageEntity>
 */
class EspnImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnImageEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnImageDto $espnImageDto): EspnImageEntity
    {
        $espnImage = new EspnImageEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnImage->buildFindByCriteriaFromDto($espnImageDto)
            ))) {
            $espnImage = $existingEntity;
        }

        return $espnImage;
    }
}
