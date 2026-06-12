<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCoach;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCoachRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnCoach as EspnCoachDto;

readonly class EspnCoachConverter implements ConverterInterface
{
    public function __construct(
        private EspnCoachRepository  $espnCoachRepository,
        private EspnAddressConverter $espnAddressConverter,
    )
    {
    }

    public function toEntity(EspnCoachDto $espnCoachDto, EspnSeason $espnSeason): EspnCoach
    {
        $espnCoach = $this->espnCoachRepository->findByDtoOrCreateEntity($espnCoachDto, $espnSeason);

        $espnCoach->setEspnId($espnCoachDto->getId());
        $espnCoach->setUid($espnCoachDto->getUid());
        $espnCoach->setFirstName($espnCoachDto->getFirstName());
        $espnCoach->setLastName($espnCoachDto->getLastName());
        $espnCoach->setExperience($espnCoachDto->getExperience());
        $espnCoach->setCollegeReference($espnCoachDto->getCollegeReference());
        $espnCoach->setPersonReference($espnCoachDto->getPersonReference());

        if (null !== $espnCoachDto->getBirthPlace()) {
            $espnCoach->setBirthPlace($this->espnAddressConverter->toEntity($espnCoachDto->getBirthPlace()));
        }

        // team entity is connected in the importer

        return $espnCoach;
    }
}
