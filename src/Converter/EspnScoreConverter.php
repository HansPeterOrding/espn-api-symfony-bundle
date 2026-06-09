<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitor;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnScore;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnScoreRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnScore as EspnScoreDto;

class EspnScoreConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnScoreRepository $espnScoreRepository,
        private readonly EspnSourceConverter $espnSourceConverter,
    ) {
    }

    public function toEntity(EspnScoreDto $espnScoreDto, EspnCompetitor $espnCompetitor): EspnScore
    {
        $espnScore = $this->espnScoreRepository->findByDtoOrCreateEntity($espnScoreDto, $espnCompetitor);

        $espnScore->setValue($espnScoreDto->getValue());
        $espnScore->setDisplayValue($espnScoreDto->getDisplayValue());
        $espnScore->setWinner($espnScoreDto->getWinner());

        if (null !== $espnScoreDto->getSource()) {
            $espnScore->setSource($this->espnSourceConverter->toEntity($espnScoreDto->getSource()));
        }

        return $espnScore;
    }
}
