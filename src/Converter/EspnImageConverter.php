<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnImage as EspnImageDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnImage as EspnImageEntity;

class EspnImageConverter implements ConverterInterface
{
    public function toEntity(EspnImageDto $espnImageDto, $espnImageEntity = null): EspnImageEntity
    {
        if (!$espnImageEntity) {
            $espnImageEntity = new EspnImageEntity();
        }

        $espnImageEntity->setHref($espnImageDto->getHref());
        $espnImageEntity->setWidth($espnImageDto->getWidth());
        $espnImageEntity->setHeight($espnImageDto->getHeight());
        $espnImageEntity->setAlt($espnImageDto->getAlt());

        $espnImageEntity->setRel($espnImageDto->getRel());

        $espnImageEntity->setLastUpdated($espnImageDto->getLastUpdated());

        return $espnImageEntity;
    }
}
