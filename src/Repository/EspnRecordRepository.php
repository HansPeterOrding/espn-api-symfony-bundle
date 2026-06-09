<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnRecord;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiClient\Dto\EspnRecord as EspnRecordDto;

/**
 * @extends ServiceEntityRepository<EspnRecord>
 */
class EspnRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnRecord::class);
    }

    public function findByDtoOrCreateEntity(
        EspnRecordDto  $dto,
        EspnTeam       $team,
        EspnSeasonType $seasonType
    ): EspnRecord
    {
        $entity = new EspnRecord();
        if (null !== ($existing = $this->findOneBy(
                $entity->buildFindByCriteriaFromDto($dto, $team, $seasonType)
            ))) {
            $entity = $existing;
        }

        return $entity;
    }
}
