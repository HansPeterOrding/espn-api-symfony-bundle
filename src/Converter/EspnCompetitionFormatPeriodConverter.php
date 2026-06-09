<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitionFormatPeriod;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetitionFormatPeriod as EspnCompetitionFormatPeriodDto;

class EspnCompetitionFormatPeriodConverter implements ConverterInterface
{
    public function toEntity(EspnCompetitionFormatPeriodDto $espnCompetitionFormatPeriodDto): EspnCompetitionFormatPeriod
    {
        $espnCompetitionFormatPeriod = new EspnCompetitionFormatPeriod();

        $espnCompetitionFormatPeriod->setPeriods($espnCompetitionFormatPeriodDto->getPeriods());
        $espnCompetitionFormatPeriod->setDisplayName($espnCompetitionFormatPeriodDto->getDisplayName());
        $espnCompetitionFormatPeriod->setSlug($espnCompetitionFormatPeriodDto->getSlug());
        $espnCompetitionFormatPeriod->setClock($espnCompetitionFormatPeriodDto->getClock());

        return $espnCompetitionFormatPeriod;
    }
}
