<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnAthlete;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnContract;
use HansPeterOrding\EspnApiClient\Dto\EspnContract as EspnContractDto;

/**
 * @extends ServiceEntityRepository<EspnContract>
 */
class EspnContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnContract::class);
    }

    public function findByAthleteOrCreateEntity(EspnAthlete $athlete, ?int $signedThrough): EspnContract
    {
        if (null !== $signedThrough) {
            $existing = $this->findOneBy([
                'athlete' => $athlete,
                'signedThrough' => $signedThrough,
            ]);
            if (null !== $existing) {
                return $existing;
            }
        }
        return new EspnContract();
    }
}
