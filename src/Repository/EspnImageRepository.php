<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnImage;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenue;
use HansPeterOrding\EspnApiClient\Dto\EspnImage as EspnImageDto;

/**
 * @extends ServiceEntityRepository<EspnImage>
 */
class EspnImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EspnImage::class);
    }

    public function findByDtoOrCreateEntity(EspnImageDto $dto, EspnTeam|EspnVenue $parent): EspnImage
    {
        $entity = new EspnImage();
        if (null !== ($existing = $this->findOneBy($entity->buildFindByCriteriaFromDto($dto, $parent)))) {
            $entity = $existing;
        }

        return $entity;
    }
}
