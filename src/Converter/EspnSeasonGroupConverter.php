<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonGroup;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonGroupRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonGroup as EspnSeasonGroupDto;

readonly class EspnSeasonGroupConverter implements ConverterInterface
{
    public function __construct(
        private EspnSeasonGroupRepository $espnSeasonGroupRepository,
    )
    {
    }

    public function toEntity(EspnSeasonGroupDto $espnSeasonGroupDto): EspnSeasonGroup
    {
        $espnSeasonGroup = $this->espnSeasonGroupRepository->findByDtoOrCreateEntity($espnSeasonGroupDto);

        $espnSeasonGroup->setEspnId($espnSeasonGroupDto->getId());
        $espnSeasonGroup->setUid($espnSeasonGroupDto->getUid());
        $espnSeasonGroup->setName($espnSeasonGroupDto->getName());
        $espnSeasonGroup->setAbbreviation($espnSeasonGroupDto->getAbbreviation());
        $espnSeasonGroup->setSlug($espnSeasonGroupDto->getSlug());
        $espnSeasonGroup->setIsConference($espnSeasonGroupDto->getIsConference());
        $espnSeasonGroup->setStandingsReference($espnSeasonGroupDto->getStandingsReference());
        $espnSeasonGroup->setTeamsReference($espnSeasonGroupDto->getTeamsReference());
        $espnSeasonGroup->setChildrenReference($espnSeasonGroupDto->getChildrenReference());
        // parent EspnSeasonGroup connected in the importer

        return $espnSeasonGroup;
    }
}
