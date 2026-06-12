<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnOfficial;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnOfficialRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnOfficial as EspnOfficialDto;

readonly class EspnOfficialConverter implements ConverterInterface
{
    public function __construct(
        private EspnOfficialRepository        $espnOfficialRepository,
        private EspnOfficialPositionConverter $espnOfficialPositionConverter,
    )
    {
    }

    public function toEntity(EspnOfficialDto $espnOfficialDto, EspnCompetition $espnCompetition): EspnOfficial
    {
        $espnOfficial = $this->espnOfficialRepository->findByDtoOrCreateEntity($espnOfficialDto, $espnCompetition);

        $espnOfficial->setEspnId($espnOfficialDto->getId());
        $espnOfficial->setFirstName($espnOfficialDto->getFirstName());
        $espnOfficial->setLastName($espnOfficialDto->getLastName());
        $espnOfficial->setFullName($espnOfficialDto->getFullName());
        $espnOfficial->setDisplayName($espnOfficialDto->getDisplayName());
        $espnOfficial->setDisplayOrder($espnOfficialDto->getOrder());

        if (null !== $espnOfficialDto->getPosition()) {
            $espnOfficial->setPosition(
                $this->espnOfficialPositionConverter->toEntity($espnOfficialDto->getPosition())
            );
        }

        return $espnOfficial;
    }
}
