<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnCompetitorScore as EspnCompetitorScoreDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitorScore as EspnCompetitorScoreEntity;

class EspnCompetitorScoreConverter implements ConverterInterface
{
    public function toEntity(EspnCompetitorScoreDto $espnCompetitorScoreDto, $espnCompetitorScoreEntity = null): EspnCompetitorScoreEntity
    {
        if (!$espnCompetitorScoreEntity) {
            $espnCompetitorScoreEntity = new EspnCompetitorScoreEntity();
        }

        $espnCompetitorScoreEntity->setValue($espnCompetitorScoreDto->getValue());
        $espnCompetitorScoreEntity->setDisplayValue($espnCompetitorScoreDto->getDisplayValue());

        return $espnCompetitorScoreEntity;
    }
}
