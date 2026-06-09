<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnOfficial;
use HansPeterOrding\EspnApiClient\Dto\EspnOfficial as EspnOfficialDto;

/**
 * @extends ServiceEntityRepository<EspnOfficial>
 */
class EspnOfficialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnOfficial::class);
    }

    public function findByDtoOrCreateEntity(EspnOfficialDto $dto, EspnCompetition $competition): EspnOfficial
    {
        $entity = new EspnOfficial();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto, $competition)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
