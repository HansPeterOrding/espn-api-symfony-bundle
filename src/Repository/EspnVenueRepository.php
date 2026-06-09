<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenue;
use HansPeterOrding\EspnApiClient\Dto\EspnVenue as EspnVenueDto;

/**
 * @extends ServiceEntityRepository<EspnVenue>
 */
class EspnVenueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnVenue::class);
    }

    public function findByDtoOrCreateEntity(EspnVenueDto $dto): EspnVenue
    {
        $entity = new EspnVenue();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
