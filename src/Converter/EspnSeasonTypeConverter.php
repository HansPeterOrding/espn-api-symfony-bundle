<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnSeasonType as EspnSeasonTypeDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType as EspnSeasonTypeEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeRepository;

class EspnSeasonTypeConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnSeasonTypeRepository $espnSeasonTypeRepository,
    )
    {
    }

    public function toEntity(EspnSeasonTypeDto $espnSeasonTypeDto): EspnSeasonTypeEntity
    {
        $espnSeasonTypeEntity = $this->espnSeasonTypeRepository->findByDtoOrCreateEntity($espnSeasonTypeDto);

        $espnSeasonTypeEntity->setEspnId($espnSeasonTypeDto->getId());
        $espnSeasonTypeEntity->setType($espnSeasonTypeDto->getType());
        $espnSeasonTypeEntity->setName($espnSeasonTypeDto->getName());
        $espnSeasonTypeEntity->setAbbreviation($espnSeasonTypeDto->getAbbreviation());
        $espnSeasonTypeEntity->setYear($espnSeasonTypeDto->getYear());
        $espnSeasonTypeEntity->setStartDate($espnSeasonTypeDto->getStartDate());
        $espnSeasonTypeEntity->setEndDate($espnSeasonTypeDto->getEndDate());
        $espnSeasonTypeEntity->setHasGroups($espnSeasonTypeDto->getHasGroups());
        $espnSeasonTypeEntity->setHasStandings($espnSeasonTypeDto->getHasStandings());
        $espnSeasonTypeEntity->setHasLegs($espnSeasonTypeDto->getHasLegs());
        $espnSeasonTypeEntity->setGroupsReference($espnSeasonTypeDto->getGroupsReference());
        $espnSeasonTypeEntity->setWeeksReference($espnSeasonTypeDto->getWeeksReference());
        $espnSeasonTypeEntity->setCorrectionsReference($espnSeasonTypeDto->getCorrectionsReference());
        $espnSeasonTypeEntity->setSlug($espnSeasonTypeDto->getSlug());

        return $espnSeasonTypeEntity;
    }
}
