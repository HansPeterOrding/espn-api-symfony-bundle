<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTeam as EspnSeasonTeamDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTeam as EspnSeasonTeamEntity;

/**
 * @extends ServiceEntityRepository<EspnSeasonTeamEntity>
 */
class EspnSeasonTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnSeasonTeamEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnSeasonTeamDto $espnSeasonTeamDto): EspnSeasonTeamEntity
    {
        $espnSeasonTeam = new EspnSeasonTeamEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnSeasonTeam->buildFindByCriteriaFromDto($espnSeasonTeamDto)
            ))) {
            $espnSeasonTeam = $existingEntity;
        }

        return $espnSeasonTeam;
    }
}
