<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnWeek;
use HansPeterOrding\EspnApiClient\Dto\EspnWeek as EspnWeekDto;

/**
 * @extends ServiceEntityRepository<EspnWeek>
 */
class EspnWeekRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnWeek::class);
    }

    public function findByDtoOrCreateEntity(EspnWeekDto $dto, EspnSeasonType $seasonType): EspnWeek
    {
        $entity = new EspnWeek();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto, $seasonType)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
