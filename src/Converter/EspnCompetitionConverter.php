<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnCompetition as EspnCompetitionDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnCompetitionTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition as EspnCompetitionEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitionRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnVenueRepository;

class EspnCompetitionConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnCompetitionRepository $espnCompetitionRepository,
        private readonly EspnVenueRepository $espnVenueRepository,
        private readonly EspnCompetitorConverter $espnCompetitorConverter,
        private readonly EspnNoteConverter $espnNoteConverter,
        private readonly EspnBroadcastConverter $espnBroadcastConverter,
        private readonly EspnCompetitionStatusConverter $espnCompetitionStatusConverter,
    )
    {
    }

    public function toEntity(EspnCompetitionDto $espnCompetitionDto): EspnCompetitionEntity
    {
        $espnCompetitionEntity = $this->espnCompetitionRepository->findByDtoOrCreateEntity($espnCompetitionDto);

        $espnCompetitionEntity->setCompetitionId($espnCompetitionDto->getId());
        $espnCompetitionEntity->setDate($espnCompetitionDto->getDate());
        $espnCompetitionEntity->setAttendance($espnCompetitionDto->getAttendance());
        $espnCompetitionEntity->setType(EspnCompetitionTypeEnum::from($espnCompetitionDto->getType()->getType()));
        $espnCompetitionEntity->setTimeValid($espnCompetitionDto->isTimeValid());
        $espnCompetitionEntity->setNeutralSite($espnCompetitionDto->isNeutralSite());
        $espnCompetitionEntity->setBoxscoreAvailable($espnCompetitionDto->isBoxscoreAvailable());
        $espnCompetitionEntity->setTicketsAvailable($espnCompetitionDto->isTicketsAvailable());

        $venue = $this->espnVenueRepository->findOneBy([
            'fullName' => $espnCompetitionDto->getVenue()->getFullName()
        ]);
        if(!$venue) {
            throw new ImportException(sprintf('Venue "%s" not found. Please first run a full venue import!', $espnCompetitionDto->getVenue()->getFullName()));
        }
        $espnCompetitionEntity->setVenue($venue);

        foreach($espnCompetitionDto->getCompetitors() as $competitor) {
            $competitorEntity = $this->espnCompetitorConverter->toEntity($espnCompetitionEntity, $competitor);
            $espnCompetitionEntity->addOrReplaceCompetitor($competitorEntity);
        }

        $notes = [];
        foreach($espnCompetitionDto->getNotes() as $note) {
            $notes[] = $this->espnNoteConverter->toEntity($note);
        }
        $espnCompetitionEntity->setNotes($notes);

        $espnCompetitionEntity->removeAllBroadcasts();
        foreach($espnCompetitionDto->getBroadcasts() as $broadcast) {
            $broadcastEntity = $this->espnBroadcastConverter->toEntity($broadcast);
            $espnCompetitionEntity->addBroadcast($broadcastEntity);
        }

        $status = $this->espnCompetitionStatusConverter->toEntity($espnCompetitionDto->getStatus(), $espnCompetitionEntity->getStatus());
        $espnCompetitionEntity->setStatus($status);

        return $espnCompetitionEntity;
    }
}
