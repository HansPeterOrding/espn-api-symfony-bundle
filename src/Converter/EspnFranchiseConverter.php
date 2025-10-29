<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnFranchise as EspnFranchiseDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnFranchise as EspnFranchiseEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnFranchiseRepository;

class EspnFranchiseConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnFranchiseRepository $espnFranchiseRepository,
        private readonly EspnVenueConverter $espnVenueConverter,
    )
    {
    }

    public function toEntity(EspnFranchiseDto $espnFranchiseDto): EspnFranchiseEntity
    {
        $espnFranchiseEntity = $this->espnFranchiseRepository->findByDtoOrCreateEntity($espnFranchiseDto);

        $espnFranchiseEntity->setFranchiseId($espnFranchiseDto->getId());
        $espnFranchiseEntity->setUid($espnFranchiseDto->getUid());
        $espnFranchiseEntity->setSlug($espnFranchiseDto->getSlug());
        $espnFranchiseEntity->setLocation($espnFranchiseDto->getLocation());
        $espnFranchiseEntity->setName($espnFranchiseDto->getName());
        $espnFranchiseEntity->setNickname($espnFranchiseDto->getNickname());
        $espnFranchiseEntity->setAbbreviation($espnFranchiseDto->getAbbreviation());
        $espnFranchiseEntity->setDisplayName($espnFranchiseDto->getDisplayName());
        $espnFranchiseEntity->setShortDisplayName($espnFranchiseDto->getShortDisplayName());
        $espnFranchiseEntity->setColor($espnFranchiseDto->getColor());
        $espnFranchiseEntity->setIsActive($espnFranchiseDto->getIsActive());

        $espnVenue = $this->espnVenueConverter->toEntity($espnFranchiseDto->getVenue());
        $espnFranchiseEntity->setVenue($espnVenue);

        return $espnFranchiseEntity;
    }
}
