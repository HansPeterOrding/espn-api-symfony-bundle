<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitionStatus;

/**
 * @extends ServiceEntityRepository<EspnCompetitionStatus>
 */
class EspnCompetitionStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnCompetitionStatus::class);
    }

    public function findByCompetitionOrCreateEntity(EspnCompetition $competition): EspnCompetitionStatus
    {
        return $this->findOneBy(['competition' => $competition]) ?? new EspnCompetitionStatus();
    }
}
