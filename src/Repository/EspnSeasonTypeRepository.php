<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonType as EspnSeasonTypeDto;

/**
 * @extends ServiceEntityRepository<EspnSeasonType>
 */
class EspnSeasonTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnSeasonType::class);
    }

    public function findByDtoOrCreateEntity(EspnSeasonTypeDto $dto, EspnSeason $season): EspnSeasonType
    {
        $entity = new EspnSeasonType();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto, $season)))) {
            $entity = $existing;
        }

        return $entity;
    }

    public function resetCurrentForSeason(EspnSeason $season): void
    {
        $this->createQueryBuilder('st')
            ->update()
            ->set('st.isCurrent', ':false')
            ->where('st.season = :season')
            ->andWhere('st.isCurrent = :true')
            ->setParameter('false', false)
            ->setParameter('true', true)
            ->setParameter('season', $season)
            ->getQuery()
            ->execute();
    }
}
