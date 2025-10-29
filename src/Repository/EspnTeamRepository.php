<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiClient\Dto\EspnTeam as EspnTeamDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam as EspnTeamEntity;

/**
 * @extends ServiceEntityRepository<EspnTeamEntity>
 */
class EspnTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnTeamEntity::class);
    }

    public function findByDtoOrCreateEntity(EspnTeamDto $espnTeamDto): EspnTeamEntity
    {
        $espnTeam = new EspnTeamEntity();
        if (null !== ($existingEntity = $this->findOneBy(
                $espnTeam->buildFindByCriteriaFromDto($espnTeamDto)
            ))) {
            $espnTeam = $existingEntity;
        }

        return $espnTeam;
    }
}
