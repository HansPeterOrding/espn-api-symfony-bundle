<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeGroup as EspnSeasonTypeGroupDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeGroup as EspnSeasonTypeGroupEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeGroupRepository;

class EspnSeasonTypeGroupConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnSeasonTypeGroupRepository $espnSeasonTypeGroupRepository,
    )
    {
    }

    public function toEntity(EspnSeasonTypeGroupDto $espnSeasonTypeGroupDto): EspnSeasonTypeGroupEntity
    {
        $espnSeasonTypeGroupEntity = $this->espnSeasonTypeGroupRepository->findByDtoOrCreateEntity($espnSeasonTypeGroupDto);

        $espnSeasonTypeGroupEntity->setUid($espnSeasonTypeGroupDto->getUid());
        $espnSeasonTypeGroupEntity->setEspnId($espnSeasonTypeGroupDto->getId());
        $espnSeasonTypeGroupEntity->setName($espnSeasonTypeGroupDto->getName());
        $espnSeasonTypeGroupEntity->setAbbreviation($espnSeasonTypeGroupDto->getAbbreviation());
        $espnSeasonTypeGroupEntity->setSeasonReference($espnSeasonTypeGroupDto->getSeasonReference());
        $espnSeasonTypeGroupEntity->setChildrenReference($espnSeasonTypeGroupDto->getChildrenReference());
        $espnSeasonTypeGroupEntity->setParentReference($espnSeasonTypeGroupDto->getParentReference());
        $espnSeasonTypeGroupEntity->setStandingsReference($espnSeasonTypeGroupDto->getStandingsReference());
        $espnSeasonTypeGroupEntity->setIsConference($espnSeasonTypeGroupDto->getIsConference());
        $espnSeasonTypeGroupEntity->setSlug($espnSeasonTypeGroupDto->getSlug());
        $espnSeasonTypeGroupEntity->setTeamsReference($espnSeasonTypeGroupDto->getTeamsReference());

        return $espnSeasonTypeGroupEntity;
    }
}
