<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnAthlete;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnNote;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\NoteParentTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnNoteRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnNote as EspnNoteDto;

readonly class EspnNoteConverter implements ConverterInterface
{
    public function __construct(
        private EspnNoteRepository $espnNoteRepository,
    )
    {
    }

    public function toEntity(EspnNoteDto $espnNoteDto, EspnAthlete|EspnTeam $parent): EspnNote
    {
        $espnNote = $this->espnNoteRepository->findByDtoOrCreateEntity($espnNoteDto, $parent);

        $espnNote->setEspnId($espnNoteDto->getId());
        $espnNote->setType($espnNoteDto->getType());
        $espnNote->setDate($espnNoteDto->getDate());
        $espnNote->setHeadline($espnNoteDto->getHeadline());
        $espnNote->setText($espnNoteDto->getText());
        $espnNote->setSource($espnNoteDto->getSource());

        $espnNote->setParentType(match (true) {
            $parent instanceof EspnAthlete => NoteParentTypeEnum::Athlete,
            $parent instanceof EspnTeam => NoteParentTypeEnum::Team,
        });

        return $espnNote;
    }
}
