<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use DateTimeImmutable;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\SeasonTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonType as EspnSeasonTypeDto;

readonly class EspnSeasonTypeConverter implements ConverterInterface
{
    public function __construct(
        private EspnSeasonTypeRepository $espnSeasonTypeRepository,
    )
    {
    }

    public function toEntity(EspnSeasonTypeDto $espnSeasonTypeDto, EspnSeason $espnSeason): EspnSeasonType
    {
        $espnSeasonType = $this->espnSeasonTypeRepository->findByDtoOrCreateEntity($espnSeasonTypeDto, $espnSeason);

        $espnSeasonType->setEspnId($espnSeasonTypeDto->getId());
        $espnSeasonType->setName($espnSeasonTypeDto->getName());
        $espnSeasonType->setAbbreviation($espnSeasonTypeDto->getAbbreviation());
        $espnSeasonType->setYear($espnSeasonTypeDto->getYear());
        $espnSeasonType->setHasGroups($espnSeasonTypeDto->getHasGroups());
        $espnSeasonType->setHasStandings($espnSeasonTypeDto->getHasStandings());
        $espnSeasonType->setHasLegs($espnSeasonTypeDto->getHasLegs());
        $espnSeasonType->setSlug($espnSeasonTypeDto->getSlug());
        $espnSeasonType->setGroupsReference($espnSeasonTypeDto->getGroupsReference());
        $espnSeasonType->setWeeksReference($espnSeasonTypeDto->getWeeksReference());
        $espnSeasonType->setCorrectionsReference($espnSeasonTypeDto->getCorrectionsReference());
        $espnSeasonType->setLeadersReference($espnSeasonTypeDto->getLeadersReference());

        if (null !== $espnSeasonTypeDto->getStartDate()) {
            $espnSeasonType->setStartDate(new DateTimeImmutable($espnSeasonTypeDto->getStartDate()));
        }

        if (null !== $espnSeasonTypeDto->getEndDate()) {
            $espnSeasonType->setEndDate(new DateTimeImmutable($espnSeasonTypeDto->getEndDate()));
        }

        if (null !== $espnSeasonTypeDto->getType()) {
            $espnSeasonType->setType(SeasonTypeEnum::tryFrom($espnSeasonTypeDto->getType()));
        }

        return $espnSeasonType;
    }
}
