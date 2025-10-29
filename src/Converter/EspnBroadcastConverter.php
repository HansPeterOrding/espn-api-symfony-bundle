<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnBroadcast as EspnBroadcastDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnBroadcastMarketEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnBroadcastMediaEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnBroadcastTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnBroadcast as EspnBroadcastEntity;

class EspnBroadcastConverter implements ConverterInterface
{
    public function toEntity(EspnBroadcastDto $espnBroadcastDto, EspnBroadcastEntity $espnBroadcastEntity = null): EspnBroadcastEntity
    {
        if(!$espnBroadcastEntity) {
            $espnBroadcastEntity = new EspnBroadcastEntity();
        }

        $espnBroadcastEntity->setType(EspnBroadcastTypeEnum::from($espnBroadcastDto->getType()->getShortName()));
        $espnBroadcastEntity->setMarket(EspnBroadcastMarketEnum::from($espnBroadcastDto->getMarket()->getType()));
        $espnBroadcastEntity->setMedia(EspnBroadcastMediaEnum::from($espnBroadcastDto->getMedia()->getShortName()));
        $espnBroadcastEntity->setLang($espnBroadcastDto->getLang());
        $espnBroadcastEntity->setRegion($espnBroadcastDto->getRegion());
        $espnBroadcastEntity->setPartnered($espnBroadcastDto->isPartnered());

        return $espnBroadcastEntity;
    }
}
