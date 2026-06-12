<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use DateTimeImmutable;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnImage;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenue;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\ImageParentTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnImageRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnImage as EspnImageDto;

readonly class EspnImageConverter implements ConverterInterface
{
    public function __construct(
        private EspnImageRepository $espnImageRepository,
    )
    {
    }

    public function toEntity(EspnImageDto $espnImageDto, EspnTeam|EspnVenue $parent): EspnImage
    {
        $espnImage = $this->espnImageRepository->findByDtoOrCreateEntity($espnImageDto, $parent);

        $espnImage->setHref($espnImageDto->getHref());
        $espnImage->setWidth($espnImageDto->getWidth());
        $espnImage->setHeight($espnImageDto->getHeight());
        $espnImage->setAlt($espnImageDto->getAlt());
        $espnImage->setRel($espnImageDto->getRel());

        if (null !== $espnImageDto->getLastUpdated()) {
            $espnImage->setLastUpdated(new DateTimeImmutable($espnImageDto->getLastUpdated()));
        }

        $espnImage->setParentType(match (true) {
            $parent instanceof EspnTeam => ImageParentTypeEnum::Team,
            $parent instanceof EspnVenue => ImageParentTypeEnum::Venue,
        });

        return $espnImage;
    }
}
