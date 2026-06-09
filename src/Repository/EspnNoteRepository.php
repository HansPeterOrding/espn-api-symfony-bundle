<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnAthlete;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnNote;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiClient\Dto\EspnNote as EspnNoteDto;

/**
 * @extends ServiceEntityRepository<EspnNote>
 */
class EspnNoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnNote::class);
    }

    public function findByDtoOrCreateEntity(EspnNoteDto $dto, EspnAthlete|EspnTeam $parent): EspnNote
    {
        $entity = new EspnNote();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto, $parent)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
