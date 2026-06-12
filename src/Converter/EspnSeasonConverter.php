<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use DateTimeImmutable;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnSeason as EspnSeasonDto;

readonly class EspnSeasonConverter implements ConverterInterface
{
    public function __construct(
        private EspnSeasonRepository $espnSeasonRepository,
    )
    {
    }

    public function toEntity(EspnSeasonDto $espnSeasonDto): EspnSeason
    {
        $espnSeason = $this->espnSeasonRepository->findByDtoOrCreateEntity($espnSeasonDto);

        $espnSeason->setEspnYear($espnSeasonDto->getYear());
        $espnSeason->setDisplayName($espnSeasonDto->getDisplayName());

        if (null !== $espnSeasonDto->getStartDate()) {
            $espnSeason->setStartDate(new DateTimeImmutable($espnSeasonDto->getStartDate()));
        }

        if (null !== $espnSeasonDto->getEndDate()) {
            $espnSeason->setEndDate(new DateTimeImmutable($espnSeasonDto->getEndDate()));
        }

        $espnSeason->setTypeReference($espnSeasonDto->getTypeReference());
        $espnSeason->setTypesReference($espnSeasonDto->getTypesReference());
        $espnSeason->setRankingsReference($espnSeasonDto->getRankingsReference());
        $espnSeason->setCoachesReference($espnSeasonDto->getCoachesReference());
        $espnSeason->setAthletesReference($espnSeasonDto->getAthletesReference());
        $espnSeason->setAwardsReference($espnSeasonDto->getAwardsReference());
        $espnSeason->setFuturesReference($espnSeasonDto->getFuturesReference());
        $espnSeason->setLeadersReference($espnSeasonDto->getLeadersReference());

        return $espnSeason;
    }
}
