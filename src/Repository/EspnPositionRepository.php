<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnPosition;
use HansPeterOrding\EspnApiClient\Dto\EspnPosition as EspnPositionDto;

/**
 * @extends ServiceEntityRepository<EspnPosition>
 */
class EspnPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnPosition::class);
    }

    public function findByDtoOrCreateEntity(EspnPositionDto $dto): EspnPosition
    {
        $entity = new EspnPosition();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
