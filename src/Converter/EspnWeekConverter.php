<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnWeek as EspnWeekDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnWeek as EspnWeekEntity;

class EspnWeekConverter implements ConverterInterface
{
    public function toEntity(EspnWeekDto $espnWeekDto, $espnWeekEntity = null): EspnWeekEntity
    {
        if (!$espnWeekEntity) {
            $espnWeekEntity = new EspnWeekEntity();
        }

        $espnWeekEntity->setNumber($espnWeekDto->getNumber());
        $espnWeekEntity->setText($espnWeekDto->getText());

        return $espnWeekEntity;
    }
}
