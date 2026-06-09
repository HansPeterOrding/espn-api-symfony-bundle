<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetition as EspnCompetitionDto;

/**
 * @extends ServiceEntityRepository<EspnCompetition>
 */
class EspnCompetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnCompetition::class);
    }

    public function findByDtoOrCreateEntity(EspnCompetitionDto $dto): EspnCompetition
    {
        $entity = new EspnCompetition();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
