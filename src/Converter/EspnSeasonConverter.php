<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnSeason as EspnSeasonDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason as EspnSeasonEntity;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;

class EspnSeasonConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnSeasonRepository $espnSeasonRepository,
    )
    {
    }

    public function toEntity(EspnSeasonDto $espnSeasonDto): EspnSeasonEntity
    {
        $espnSeasonEntity = $this->espnSeasonRepository->findByDtoOrCreateEntity($espnSeasonDto);

        $espnSeasonEntity->setYear($espnSeasonDto->getYear());
        $espnSeasonEntity->setName($espnSeasonDto->getName());
        $espnSeasonEntity->setDisplayName($espnSeasonDto->getDisplayName());
        $espnSeasonEntity->setHalf($espnSeasonDto->getHalf());

        return $espnSeasonEntity;
    }
}
