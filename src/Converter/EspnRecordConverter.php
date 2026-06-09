<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnRecord;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnRecordRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnRecord as EspnRecordDto;

class EspnRecordConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnRecordRepository $espnRecordRepository,
    )
    {
    }

    public function toEntity(
        EspnRecordDto  $espnRecordDto,
        EspnTeam       $espnTeam,
        EspnSeasonType $espnSeasonType,
        EspnSeason     $espnSeason
    ): EspnRecord
    {
        $espnRecord = $this->espnRecordRepository->findByDtoOrCreateEntity(
            $espnRecordDto,
            $espnTeam,
            $espnSeasonType
        );

        $espnRecord->setEspnId($espnRecordDto->getId());
        $espnRecord->setName($espnRecordDto->getName());
        $espnRecord->setDisplayName($espnRecordDto->getDisplayName());
        $espnRecord->setShortDisplayName($espnRecordDto->getShortDisplayName());
        $espnRecord->setDescription($espnRecordDto->getDescription());
        $espnRecord->setAbbreviation($espnRecordDto->getAbbreviation());
        $espnRecord->setType($espnRecordDto->getType());
        $espnRecord->setSummary($espnRecordDto->getSummary());
        $espnRecord->setDisplayValue($espnRecordDto->getDisplayValue());
        $espnRecord->setValue($espnRecordDto->getValue());

        $stats = array_map(
            static fn($statDto) => [
                'name' => $statDto->getName(),
                'displayName' => $statDto->getDisplayName(),
                'shortDisplayName' => $statDto->getShortDisplayName(),
                'description' => $statDto->getDescription(),
                'abbreviation' => $statDto->getAbbreviation(),
                'type' => $statDto->getType(),
                'value' => $statDto->getValue(),
                'displayValue' => $statDto->getDisplayValue(),
            ],
            $espnRecordDto->getStats() ?? []
        );
        $espnRecord->setStats($stats ?: null);

        $espnRecord->setTeam($espnTeam);
        $espnRecord->setSeasonType($espnSeasonType);
        $espnRecord->setSeason($espnSeason);

        return $espnRecord;
    }
}
