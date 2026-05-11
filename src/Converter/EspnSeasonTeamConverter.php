<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTeam as EspnSeasonTeamDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTeam as EspnSeasonTeamEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTeamRepository;

class EspnSeasonTeamConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnSeasonTeamRepository $espnSeasonTeamRepository,
        private readonly EspnImageConverter $imageConverter,
        private readonly EspnLinkConverter  $linkConverter,
    )
    {
    }

    public function toEntity(EspnSeasonTeamDto $espnSeasonTeamDto): EspnSeasonTeamEntity
    {
        $espnSeasonTeamEntity = $this->espnSeasonTeamRepository->findByDtoOrCreateEntity($espnSeasonTeamDto);

        $espnSeasonTeamEntity->setEspnId($espnSeasonTeamDto->getId());
        $espnSeasonTeamEntity->setGuid($espnSeasonTeamDto->getGuid());
        $espnSeasonTeamEntity->setUid($espnSeasonTeamDto->getUid());
        $espnSeasonTeamEntity->setAlternateIds($espnSeasonTeamDto->getAlternateIds());
        $espnSeasonTeamEntity->setSlug($espnSeasonTeamDto->getSlug());
        $espnSeasonTeamEntity->setLocation($espnSeasonTeamDto->getLocation());
        $espnSeasonTeamEntity->setName($espnSeasonTeamDto->getName());
        $espnSeasonTeamEntity->setNickname($espnSeasonTeamDto->getNickname());
        $espnSeasonTeamEntity->setAbbreviation($espnSeasonTeamDto->getAbbreviation());
        $espnSeasonTeamEntity->setDisplayName($espnSeasonTeamDto->getDisplayName());
        $espnSeasonTeamEntity->setShortDisplayName($espnSeasonTeamDto->getShortDisplayName());
        $espnSeasonTeamEntity->setColor($espnSeasonTeamDto->getColor());
        $espnSeasonTeamEntity->setAlternateColor($espnSeasonTeamDto->getAlternateColor());
        $espnSeasonTeamEntity->setIsActive($espnSeasonTeamDto->getIsActive());
        $espnSeasonTeamEntity->setIsAllStar($espnSeasonTeamDto->getIsAllStar());

        $espnSeasonTeamEntity->removeAllLogos();
        foreach ($espnSeasonTeamDto->getLogos() as $logo) {
            $logoEntity = $this->imageConverter->toEntity($logo);
            $espnSeasonTeamEntity->addLogo($logoEntity);
        }

        $espnSeasonTeamEntity->setRecordReference($espnSeasonTeamDto->getRecordReference());
        $espnSeasonTeamEntity->setOddsRecordsReference($espnSeasonTeamDto->getOddsRecordsReference());
        $espnSeasonTeamEntity->setAthletesReference($espnSeasonTeamDto->getAthletesReference());
        $espnSeasonTeamEntity->setVenueReference($espnSeasonTeamDto->getVenueReference());
        $espnSeasonTeamEntity->setGroupsReference($espnSeasonTeamDto->getGroupsReference());
        $espnSeasonTeamEntity->setRanksReference($espnSeasonTeamDto->getRanksReference());
        $espnSeasonTeamEntity->setStatisticsReference($espnSeasonTeamDto->getStatisticsReference());
        $espnSeasonTeamEntity->setLeadersReference($espnSeasonTeamDto->getLeadersReference());

        $espnSeasonTeamEntity->removeAllLinks();
        foreach ($espnSeasonTeamDto->getLinks() as $link) {
            $linkEntity = $this->linkConverter->toEntity($link);
            $espnSeasonTeamEntity->addLink($linkEntity);
        }

        $espnSeasonTeamEntity->setInjuriesReference($espnSeasonTeamDto->getInjuriesReference());
        $espnSeasonTeamEntity->setNotesReference($espnSeasonTeamDto->getNotesReference());
        $espnSeasonTeamEntity->setAgainstTheSpreadRecordsReference($espnSeasonTeamDto->getAgainstTheSpreadRecordsReference());
        $espnSeasonTeamEntity->setAwardsReference($espnSeasonTeamDto->getAwardsReference());
        $espnSeasonTeamEntity->setFranchiseReference($espnSeasonTeamDto->getFranchiseReference());
        $espnSeasonTeamEntity->setDepthChartsReference($espnSeasonTeamDto->getDepthChartsReference());
        $espnSeasonTeamEntity->setProjectionReference($espnSeasonTeamDto->getProjectionReference());
        $espnSeasonTeamEntity->setEventsReference($espnSeasonTeamDto->getEventsReference());
        $espnSeasonTeamEntity->setTransactionsReference($espnSeasonTeamDto->getTransactionsReference());
        $espnSeasonTeamEntity->setCoachesReference($espnSeasonTeamDto->getCoachesReference());
        $espnSeasonTeamEntity->setAttendanceReference($espnSeasonTeamDto->getAttendanceReference());

        return $espnSeasonTeamEntity;
    }
}
