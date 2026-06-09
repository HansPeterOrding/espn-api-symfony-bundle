<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnEvent;
use HansPeterOrding\EspnApiClient\Dto\EspnEvent as EspnEventDto;

/**
 * @extends ServiceEntityRepository<EspnEvent>
 */
class EspnEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnEvent::class);
    }

    public function findByDtoOrCreateEntity(EspnEventDto $dto): EspnEvent
    {
        $entity = new EspnEvent();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
