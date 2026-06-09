<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnAthlete;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnInjury;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiClient\Dto\EspnInjury as EspnInjuryDto;

/**
 * @extends ServiceEntityRepository<EspnInjury>
 */
class EspnInjuryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnInjury::class);
    }

    public function findByDtoOrCreateEntity(EspnInjuryDto $dto): EspnInjury
    {
        $entity = new EspnInjury();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto)))) {
            $entity = $existing;
        }

        return $entity;
    }

    public function deleteByAthlete(EspnAthlete $athlete): void
    {
        // Find all injuries connected to any athlete with the same ESPN id (across all seasons)
        $injuries = $this->createQueryBuilder('i')
            ->join('i.athletes', 'a')
            ->where('a.espnId = :espnId')
            ->setParameter('espnId', $athlete->getEspnId())
            ->getQuery()
            ->getResult();

        $em = $this->getEntityManager();
        foreach ($injuries as $injury) {
            // Remove all athlete connections with matching espnId
            foreach ($injury->getAthletes() as $connectedAthlete) {
                if ($connectedAthlete->getEspnId() === $athlete->getEspnId()) {
                    $injury->removeAthlete($connectedAthlete);
                }
            }
            // Delete injury entirely if no athletes remain
            if ($injury->getAthletes()->isEmpty()) {
                $em->remove($injury);
            }
        }

        $em->flush();
    }

    public function deleteByTeam(EspnTeam $team): void
    {
        $this->createQueryBuilder('i')
            ->delete()
            ->where('i.team = :team')
            ->setParameter('team', $team)
            ->getQuery()
            ->execute();
    }
}
