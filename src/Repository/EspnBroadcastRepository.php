<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnBroadcast;

/**
 * @extends ServiceEntityRepository<EspnBroadcast>
 */
class EspnBroadcastRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnBroadcast::class);
    }
}
