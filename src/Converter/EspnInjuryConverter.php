<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use DateTimeImmutable;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnInjury;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\InjuryStatusEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnInjuryRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnInjury as EspnInjuryDto;

class EspnInjuryConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnInjuryRepository $espnInjuryRepository,
        private readonly EspnSourceConverter $espnSourceConverter,
        private readonly EspnInjuryTypeConverter $espnInjuryTypeConverter,
    ) {
    }

    public function toEntity(EspnInjuryDto $espnInjuryDto): EspnInjury
    {
        $espnInjury = $this->espnInjuryRepository->findByDtoOrCreateEntity($espnInjuryDto);

        $espnInjury->setEspnId($espnInjuryDto->getId());
        $espnInjury->setLongComment($espnInjuryDto->getLongComment());
        $espnInjury->setShortComment($espnInjuryDto->getShortComment());

        if (null !== $espnInjuryDto->getStatus()) {
            $espnInjury->setStatus(InjuryStatusEnum::tryFrom($espnInjuryDto->getStatus()));
        }

        if (null !== $espnInjuryDto->getDate()) {
            $espnInjury->setDate(new DateTimeImmutable($espnInjuryDto->getDate()));
        }

        if (null !== $espnInjuryDto->getSource()) {
            $espnInjury->setSource($this->espnSourceConverter->toEntity($espnInjuryDto->getSource()));
        }

        if (null !== $espnInjuryDto->getType()) {
            $espnInjury->setType($this->espnInjuryTypeConverter->toEntity($espnInjuryDto->getType()));
        }

        // athlete and team entity relations connected in the importer

        return $espnInjury;
    }
}
