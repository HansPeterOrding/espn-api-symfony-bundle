<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeamRecord;

/**
 * @extends ServiceEntityRepository<EspnTeamRecord>
 */
class EspnTeamRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnTeamRecord::class);
    }
}
