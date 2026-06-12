<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use DateTimeImmutable;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitionRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetition as EspnCompetitionDto;

readonly class EspnCompetitionConverter implements ConverterInterface
{
    public function __construct(
        private EspnCompetitionRepository      $espnCompetitionRepository,
        private EspnCompetitionTypeConverter   $espnCompetitionTypeConverter,
        private EspnCompetitionFormatConverter $espnCompetitionFormatConverter,
        private EspnSourceConverter            $espnSourceConverter,
    )
    {
    }

    public function toEntity(EspnCompetitionDto $espnCompetitionDto): EspnCompetition
    {
        $espnCompetition = $this->espnCompetitionRepository->findByDtoOrCreateEntity($espnCompetitionDto);

        $espnCompetition->setEspnId($espnCompetitionDto->getId());
        $espnCompetition->setGuid($espnCompetitionDto->getGuid());
        $espnCompetition->setUid($espnCompetitionDto->getUid());
        $espnCompetition->setAttendance($espnCompetitionDto->getAttendance());
        $espnCompetition->setTimeValid($espnCompetitionDto->getTimeValid());
        $espnCompetition->setDateValid($espnCompetitionDto->getDateValid());
        $espnCompetition->setNeutralSite($espnCompetitionDto->getNeutralSite());
        $espnCompetition->setDivisionCompetition($espnCompetitionDto->getDivisionCompetition());
        $espnCompetition->setConferenceCompetition($espnCompetitionDto->getConferenceCompetition());
        $espnCompetition->setPreviewAvailable($espnCompetitionDto->getPreviewAvailable());
        $espnCompetition->setRecapAvailable($espnCompetitionDto->getRecapAvailable());
        $espnCompetition->setBoxscoreAvailable($espnCompetitionDto->getBoxscoreAvailable());
        $espnCompetition->setLineupAvailable($espnCompetitionDto->getLineupAvailable());
        $espnCompetition->setGamecastAvailable($espnCompetitionDto->getGamecastAvailable());
        $espnCompetition->setPlayByPlayAvailable($espnCompetitionDto->getPlayByPlayAvailable());
        $espnCompetition->setConversationAvailable($espnCompetitionDto->getConversationAvailable());
        $espnCompetition->setCommentaryAvailable($espnCompetitionDto->getCommentaryAvailable());
        $espnCompetition->setPickcenterAvailable($espnCompetitionDto->getPickcenterAvailable());
        $espnCompetition->setSummaryAvailable($espnCompetitionDto->getSummaryAvailable());
        $espnCompetition->setLiveAvailable($espnCompetitionDto->getLiveAvailable());
        $espnCompetition->setTicketsAvailable($espnCompetitionDto->getTicketsAvailable());
        $espnCompetition->setHighlightsAvailable($espnCompetitionDto->getHighlightsAvailable());
        $espnCompetition->setOnWatchESPN($espnCompetitionDto->getOnWatchESPN());
        $espnCompetition->setRecent($espnCompetitionDto->getRecent());
        $espnCompetition->setBracketAvailable($espnCompetitionDto->getBracketAvailable());
        $espnCompetition->setWallclockAvailable($espnCompetitionDto->getWallclockAvailable());
        $espnCompetition->setHasDefensiveStats($espnCompetitionDto->getHasDefensiveStats());
        $espnCompetition->setVenueReference($espnCompetitionDto->getVenueReference());
        $espnCompetition->setStatusReference($espnCompetitionDto->getStatusReference());
        $espnCompetition->setSituationReference($espnCompetitionDto->getSituationReference());
        $espnCompetition->setOddsReference($espnCompetitionDto->getOddsReference());
        $espnCompetition->setBroadcastsReference($espnCompetitionDto->getBroadcastsReference());
        $espnCompetition->setOfficialsReference($espnCompetitionDto->getOfficialsReference());
        $espnCompetition->setLeadersReference($espnCompetitionDto->getLeadersReference());
        $espnCompetition->setPredicatorReference($espnCompetitionDto->getPredicatorReference());
        $espnCompetition->setProbabilitiesReference($espnCompetitionDto->getProbabilitiesReference());
        $espnCompetition->setPowerIndexesReference($espnCompetitionDto->getPowerIndexesReference());
        $espnCompetition->setRelevancyReference($espnCompetitionDto->getRelevancyReference());
        $espnCompetition->setDrivesReference($espnCompetitionDto->getDrivesReference());

        if (null !== $espnCompetitionDto->getDate()) {
            $espnCompetition->setDate(new DateTimeImmutable($espnCompetitionDto->getDate()));
        }

        if (null !== $espnCompetitionDto->getType()) {
            $espnCompetition->setType(
                $this->espnCompetitionTypeConverter->toEntity($espnCompetitionDto->getType())
            );
        }

        if (null !== $espnCompetitionDto->getFormat()) {
            $espnCompetition->setFormat(
                $this->espnCompetitionFormatConverter->toEntity($espnCompetitionDto->getFormat())
            );
        }

        if (null !== $espnCompetitionDto->getGameSource()) {
            $espnCompetition->setGameSource(
                $this->espnSourceConverter->toEntity($espnCompetitionDto->getGameSource())
            );
        }

        if (null !== $espnCompetitionDto->getBoxscoreSource()) {
            $espnCompetition->setBoxscoreSource(
                $this->espnSourceConverter->toEntity($espnCompetitionDto->getBoxscoreSource())
            );
        }

        if (null !== $espnCompetitionDto->getPlayByPlaySource()) {
            $espnCompetition->setPlayByPlaySource(
                $this->espnSourceConverter->toEntity($espnCompetitionDto->getPlayByPlaySource())
            );
        }

        if (null !== $espnCompetitionDto->getLinescoreSource()) {
            $espnCompetition->setLinescoreSource(
                $this->espnSourceConverter->toEntity($espnCompetitionDto->getLinescoreSource())
            );
        }

        if (null !== $espnCompetitionDto->getStatsSource()) {
            $espnCompetition->setStatsSource(
                $this->espnSourceConverter->toEntity($espnCompetitionDto->getStatsSource())
            );
        }

        // event and venue entity relations connected in the importer

        return $espnCompetition;
    }
}
