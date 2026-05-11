<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeGroup as EspnSeasonTypeGroupDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeGroup as EspnSeasonTypeGroupEntity;

/**
 * @extends ServiceEntityRepository<ording\EspnSeasonGroup>
 */
class EspnSeasonTypeGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnSeasonTypeGroupEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnSeasonTypeGroupDto $espnSeasonTypeGroupDto): EspnSeasonTypeGroupEntity
    {
        $espnSeasonTypeGroup = new EspnSeasonTypeGroupEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnSeasonTypeGroup->buildFindByCriteriaFromDto($espnSeasonTypeGroupDto)
            ))) {
            $espnSeasonTypeGroup = $existingEntity;
        }

        return $espnSeasonTypeGroup;
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
