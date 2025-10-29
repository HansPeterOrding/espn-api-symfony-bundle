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
}
