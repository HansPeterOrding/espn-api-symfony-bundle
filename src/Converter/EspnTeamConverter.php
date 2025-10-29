<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnTeam as EspnTeamDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam as EspnTeamEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;

class EspnTeamConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnTeamRepository $espnTeamRepository,
        private readonly EspnImageConverter $imageConverter,
        private readonly EspnTeamRecordConverter $teamRecordConverter,
        private readonly EspnFranchiseConverter $franchiseConverter,
    )
    {
    }

    public function toEntity(EspnTeamDto $espnTeamDto): EspnTeamEntity
    {
        $espnTeamEntity = $this->espnTeamRepository->findByDtoOrCreateEntity($espnTeamDto);

        $espnTeamEntity->setTeamId($espnTeamDto->getId());
        $espnTeamEntity->setUid($espnTeamDto->getUid());
        $espnTeamEntity->setSlug($espnTeamDto->getSlug());
        $espnTeamEntity->setLocation($espnTeamDto->getLocation());
        $espnTeamEntity->setName($espnTeamDto->getName());
        $espnTeamEntity->setDisplayName($espnTeamDto->getDisplayName());
        $espnTeamEntity->setShortDisplayName($espnTeamDto->getShortDisplayName());
        $espnTeamEntity->setAbbreviation($espnTeamDto->getAbbreviation());
        $espnTeamEntity->setNickname($espnTeamDto->getNickname());
        $espnTeamEntity->setAlternateId($espnTeamDto->getAlternateId());
        $espnTeamEntity->setStandingSummary($espnTeamDto->getStandingSummary());

        $espnTeamEntity->removeAllLogos();
        foreach($espnTeamDto->getLogos() as $logo) {
            $logoEntity = $this->imageConverter->toEntity($logo);
            $espnTeamEntity->addLogo($logoEntity);
        }

        $recordEntity = $this->teamRecordConverter->toEntity($espnTeamDto->getRecord(), $espnTeamEntity->getRecord());
        $espnTeamEntity->setRecord($recordEntity);

        $franchiseEntity = $this->franchiseConverter->toEntity($espnTeamDto->getFranchise());
        $espnTeamEntity->setFranchise($franchiseEntity);

        return $espnTeamEntity;
    }
}
