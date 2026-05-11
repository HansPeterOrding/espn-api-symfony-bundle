<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeTeamRecord as EspnSeasonTypeTeamRecordDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeTeamRecord as EspnSeasonTypeTeamRecordEntity;

/**
 * @extends ServiceEntityRepository<EspnSeasonTypeTeamRecord>
 */
class EspnSeasonTypeTeamRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnSeasonTypeTeamRecordEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnSeasonType $espnSeasonType, EspnSeasonTeam $espnSeasonTeam, EspnSeasonTypeTeamRecordDto $espnSeasonTypeTeamRecordDto): EspnSeasonTypeTeamRecordEntity
    {
        $espnSeasonTypeTeamRecord = new EspnSeasonTypeTeamRecordEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnSeasonTypeTeamRecord->buildFindByCriteriaFromDto($espnSeasonType, $espnSeasonTeam, $espnSeasonTypeTeamRecordDto)
            ))) {
            $espnSeasonTypeTeamRecord = $existingEntity;
        }

        return $espnSeasonTypeTeamRecord;
    }

//    /**
//     * @return EspnSeasonTypeTeamRecord[] Returns an array of EspnSeasonTypeTeamRecord objects
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

//    public function findOneBySomeField($value): ?EspnSeasonTypeTeamRecord
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
