<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeWeek as EspnSeasonTypeWeekDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeWeek as EspnSeasonTypeWeekEntity;

/**
 * @extends ServiceEntityRepository<ording\EspnSeasonGroup>
 */
class EspnSeasonTypeWeekRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnSeasonTypeWeekEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnSeasonType $espnSeasonType, EspnSeasonTypeWeekDto $espnSeasonTypeWeekDto): EspnSeasonTypeWeekEntity
    {
        $espnSeasonTypeWeek = new EspnSeasonTypeWeekEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnSeasonTypeWeek->buildFindByCriteriaFromDto($espnSeasonType, $espnSeasonTypeWeekDto)
            ))) {
            $espnSeasonTypeWeek = $existingEntity;
        }

        return $espnSeasonTypeWeek;
    }

//    /**
//     * @return EspnSeasonGroup[] Returns an array of EspnSeasonGroup objects
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

//    public function findOneBySomeField($value): ?EspnSeasonGroup
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
