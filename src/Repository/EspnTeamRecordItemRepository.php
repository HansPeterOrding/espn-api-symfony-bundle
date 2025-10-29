<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnTeamRecordItem as EspnTeamRecordItemDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeamRecordItem as EspnTeamRecordItemEntity;

/**
 * @extends ServiceEntityRepository<EspnTeamRecordItemEntity>
 */
class EspnTeamRecordItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnTeamRecordItemEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnTeamRecordItemDto $espnTeamRecordItemDto): EspnTeamRecordItemEntity
    {
        $espnTeamRecordItem = new EspnTeamRecordItemEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnTeamRecordItem->buildFindByCriteriaFromDto($espnTeamRecordItemDto)
            ))) {
            $espnTeamRecordItem = $existingEntity;
        }

        return $espnTeamRecordItem;
    }
}
