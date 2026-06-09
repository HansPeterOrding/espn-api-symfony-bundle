<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnFranchise;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnFranchiseRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnFranchise as EspnFranchiseDto;

class EspnFranchiseConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnFranchiseRepository $espnFranchiseRepository,
    )
    {
    }

    public function toEntity(EspnFranchiseDto $espnFranchiseDto): EspnFranchise
    {
        $espnFranchise = $this->espnFranchiseRepository->findByDtoOrCreateEntity($espnFranchiseDto);

        $espnFranchise->setEspnId($espnFranchiseDto->getId());
        $espnFranchise->setUid($espnFranchiseDto->getUid());
        $espnFranchise->setSlug($espnFranchiseDto->getSlug());
        $espnFranchise->setLocation($espnFranchiseDto->getLocation());
        $espnFranchise->setName($espnFranchiseDto->getName());
        $espnFranchise->setNickname($espnFranchiseDto->getNickname());
        $espnFranchise->setAbbreviation($espnFranchiseDto->getAbbreviation());
        $espnFranchise->setDisplayName($espnFranchiseDto->getDisplayName());
        $espnFranchise->setShortDisplayName($espnFranchiseDto->getShortDisplayName());
        $espnFranchise->setColor($espnFranchiseDto->getColor());
        $espnFranchise->setIsActive($espnFranchiseDto->getIsActive());
        $espnFranchise->setVenueReference($espnFranchiseDto->getVenueReference());
        // venue and currentTeam entity relations connected in the importer

        return $espnFranchise;
    }
}
