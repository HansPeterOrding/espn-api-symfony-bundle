<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnPosition;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnPositionRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnPosition as EspnPositionDto;

class EspnPositionConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnPositionRepository $espnPositionRepository,
    ) {
    }

    public function toEntity(EspnPositionDto $espnPositionDto): EspnPosition
    {
        $espnPosition = $this->espnPositionRepository->findByDtoOrCreateEntity($espnPositionDto);

        $espnPosition->setEspnId($espnPositionDto->getId());
        $espnPosition->setName($espnPositionDto->getName());
        $espnPosition->setDisplayName($espnPositionDto->getDisplayName());
        $espnPosition->setAbbreviation($espnPositionDto->getAbbreviation());
        $espnPosition->setLeaf($espnPositionDto->getLeaf());
        // parent EspnPosition entity is connected in the importer

        return $espnPosition;
    }
}
