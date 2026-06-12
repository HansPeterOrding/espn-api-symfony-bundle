<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSource;
use HansPeterOrding\EspnApiClient\Dto\EspnSource as EspnSourceDto;

class EspnSourceConverter implements ConverterInterface
{
    public function toEntity(EspnSourceDto $espnSourceDto): EspnSource
    {
        $espnSource = new EspnSource();

        $espnSource->setEspnId($espnSourceDto->getId());
        $espnSource->setDescription($espnSourceDto->getDescription());
        $espnSource->setState($espnSourceDto->getState());

        return $espnSource;
    }
}
