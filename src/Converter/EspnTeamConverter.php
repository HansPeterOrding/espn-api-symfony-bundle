<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnTeam as EspnTeamDto;

class EspnTeamConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnTeamRepository $espnTeamRepository,
        private readonly EspnImageConverter $espnImageConverter,
    ) {
    }

    public function toEntity(EspnTeamDto $espnTeamDto): EspnTeam
    {
        $espnTeam = $this->espnTeamRepository->findByDtoOrCreateEntity($espnTeamDto);

        $espnTeam->setEspnId($espnTeamDto->getId());
        $espnTeam->setGuid($espnTeamDto->getGuid());
        $espnTeam->setUid($espnTeamDto->getUid());
        $espnTeam->setAlternateIds($espnTeamDto->getAlternateIds());
        $espnTeam->setSlug($espnTeamDto->getSlug());
        $espnTeam->setLocation($espnTeamDto->getLocation());
        $espnTeam->setName($espnTeamDto->getName());
        $espnTeam->setNickname($espnTeamDto->getNickname());
        $espnTeam->setAbbreviation($espnTeamDto->getAbbreviation());
        $espnTeam->setDisplayName($espnTeamDto->getDisplayName());
        $espnTeam->setShortDisplayName($espnTeamDto->getShortDisplayName());
        $espnTeam->setColor($espnTeamDto->getColor());
        $espnTeam->setAlternateColor($espnTeamDto->getAlternateColor());
        $espnTeam->setIsActive($espnTeamDto->getIsActive());
        $espnTeam->setIsAllStar($espnTeamDto->getIsAllStar());
        $espnTeam->setRecordReference($espnTeamDto->getRecordReference());
        $espnTeam->setOddsRecordsReference($espnTeamDto->getOddsRecordsReference());
        $espnTeam->setAthletesReference($espnTeamDto->getAthletesReference());
        $espnTeam->setVenueReference($espnTeamDto->getVenueReference());
        $espnTeam->setGroupsReference($espnTeamDto->getGroupsReference());
        $espnTeam->setRanksReference($espnTeamDto->getRanksReference());
        $espnTeam->setStatisticsReference($espnTeamDto->getStatisticsReference());
        $espnTeam->setLeadersReference($espnTeamDto->getLeadersReference());
        $espnTeam->setInjuriesReference($espnTeamDto->getInjuriesReference());
        $espnTeam->setNotesReference($espnTeamDto->getNotesReference());
        $espnTeam->setAgainstTheSpreadRecordsReference($espnTeamDto->getAgainstTheSpreadRecordsReference());
        $espnTeam->setAwardsReference($espnTeamDto->getAwardsReference());
        $espnTeam->setFranchiseReference($espnTeamDto->getFranchiseReference());
        $espnTeam->setDepthChartsReference($espnTeamDto->getDepthChartsReference());
        $espnTeam->setProjectionReference($espnTeamDto->getProjectionReference());
        $espnTeam->setEventsReference($espnTeamDto->getEventsReference());
        $espnTeam->setTransactionsReference($espnTeamDto->getTransactionsReference());
        $espnTeam->setCoachesReference($espnTeamDto->getCoachesReference());
        $espnTeam->setAttendanceReference($espnTeamDto->getAttendanceReference());

        foreach ($espnTeamDto->getLogos() as $espnImageDto) {
            $espnTeam->addOrReplaceImage($this->espnImageConverter->toEntity($espnImageDto, $espnTeam));
        }

        return $espnTeam;
    }
}
