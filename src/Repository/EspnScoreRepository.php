<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitor;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnScore;
use HansPeterOrding\EspnApiClient\Dto\EspnScore as EspnScoreDto;

/**
 * @extends ServiceEntityRepository<EspnScore>
 */
class EspnScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnScore::class);
    }

    public function findByDtoOrCreateEntity(EspnScoreDto $dto, EspnCompetitor $competitor): EspnScore
    {
        $entity = new EspnScore();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto, $competitor)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
