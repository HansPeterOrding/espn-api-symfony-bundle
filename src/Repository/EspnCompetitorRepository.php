<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitor;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetitor as EspnCompetitorDto;

/**
 * @extends ServiceEntityRepository<EspnCompetitor>
 */
class EspnCompetitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnCompetitor::class);
    }

    public function findByDtoOrCreateEntity(EspnCompetitorDto $dto, EspnCompetition $competition): EspnCompetitor
    {
        $entity = new EspnCompetitor();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto, $competition)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
