<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnVenue as EspnVenueDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenue as EspnVenueEntity;

/**
 * @extends ServiceEntityRepository<EspnVenueEntity>
 */
class EspnVenueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnVenueEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnVenueDto $espnVenueDto): EspnVenueEntity
    {
        $espnVenue = new EspnVenueEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnVenue->buildFindByCriteriaFromDto($espnVenueDto)
            ))) {
            $espnVenue = $existingEntity;
        }

        return $espnVenue;
    }

    //    /**
    //     * @return EspnVenue[] Returns an array of EspnVenue objects
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

    //    public function findOneBySomeField($value): ?EspnVenue
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
