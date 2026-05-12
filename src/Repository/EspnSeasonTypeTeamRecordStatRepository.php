<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeTeamRecordStat as EspnSeasonTypeTeamRecordStatDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeTeamRecord;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeTeamRecordStat as EspnSeasonTypeTeamRecordStatEntity;

/**
 * @extends ServiceEntityRepository<EspnSeasonTypeTeamRecordStatEntity>
 */
class EspnSeasonTypeTeamRecordStatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnSeasonTypeTeamRecordStatEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnSeasonTypeTeamRecord $espnSeasonTypeTeamRecord, EspnSeasonTypeTeamRecordStatDto $espnSeasonTypeTeamRecordStatDto): EspnSeasonTypeTeamRecordStatEntity
    {
        $espnSeasonTypeTeamRecordStat = new EspnSeasonTypeTeamRecordStatEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnSeasonTypeTeamRecordStat->buildFindByCriteriaFromDto($espnSeasonTypeTeamRecord, $espnSeasonTypeTeamRecordStatDto)
            ))) {
            $espnSeasonTypeTeamRecordStat = $existingEntity;
        }

        return $espnSeasonTypeTeamRecordStat;
    }

    //    /**
    //     * @return EspnSeasonTypeTeamRecordStatStat[] Returns an array of EspnSeasonTypeTeamRecordStatStat objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?EspnSeasonTypeTeamRecordStatStat
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
