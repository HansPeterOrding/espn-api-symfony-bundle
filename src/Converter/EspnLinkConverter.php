<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiClient\Dto\EspnLink as EspnLinkDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnLink as EspnLinkEntity;

class EspnLinkConverter implements ConverterInterface
{
    public function toEntity(EspnLinkDto $espnLinkDto, $espnLinkEntity = null): EspnLinkEntity
    {
        if (!$espnLinkEntity) {
            $espnLinkEntity = new EspnLinkEntity();
        }

        $espnLinkEntity->setLanguage($espnLinkDto->getLanguage());
        $espnLinkEntity->setRel($espnLinkDto->getRel());
        $espnLinkEntity->setHref($espnLinkDto->getHref());
        $espnLinkEntity->setText($espnLinkDto->getText());
        $espnLinkEntity->setShortText($espnLinkDto->getShortText());
        $espnLinkEntity->setIsExternal($espnLinkDto->getIsExternal());
        $espnLinkEntity->setIsPremium($espnLinkDto->getIsPremium());

        return $espnLinkEntity;
    }
}
