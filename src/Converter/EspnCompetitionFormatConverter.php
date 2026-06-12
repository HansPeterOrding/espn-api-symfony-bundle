<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitionFormat;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetitionFormat as EspnCompetitionFormatDto;

readonly class EspnCompetitionFormatConverter implements ConverterInterface
{
    public function __construct(
        private EspnCompetitionFormatPeriodConverter $espnCompetitionFormatPeriodConverter,
    )
    {
    }

    public function toEntity(EspnCompetitionFormatDto $espnCompetitionFormatDto): EspnCompetitionFormat
    {
        $espnCompetitionFormat = new EspnCompetitionFormat();

        if (null !== $espnCompetitionFormatDto->getRegulation()) {
            $espnCompetitionFormat->setRegulation(
                $this->espnCompetitionFormatPeriodConverter->toEntity($espnCompetitionFormatDto->getRegulation())
            );
        }

        if (null !== $espnCompetitionFormatDto->getOvertime()) {
            $espnCompetitionFormat->setOvertime(
                $this->espnCompetitionFormatPeriodConverter->toEntity($espnCompetitionFormatDto->getOvertime())
            );
        }

        if (null !== $espnCompetitionFormatDto->getSuddenDeath()) {
            $espnCompetitionFormat->setSuddenDeath(
                $this->espnCompetitionFormatPeriodConverter->toEntity($espnCompetitionFormatDto->getSuddenDeath())
            );
        }

        return $espnCompetitionFormat;
    }
}
