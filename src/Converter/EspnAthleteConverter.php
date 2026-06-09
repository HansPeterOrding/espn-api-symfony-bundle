<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnAthlete;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeason;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnAthleteRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnAthlete as EspnAthleteDto;

class EspnAthleteConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnAthleteRepository      $espnAthleteRepository,
        private readonly EspnAddressConverter       $espnAddressConverter,
        private readonly EspnAthleteStatusConverter $espnAthleteStatusConverter,
    )
    {
    }

    public function toEntity(EspnAthleteDto $espnAthleteDto, EspnSeason $espnSeason): EspnAthlete
    {
        $espnAthlete = $this->espnAthleteRepository->findByDtoOrCreateEntity($espnAthleteDto, $espnSeason);

        $espnAthlete->setEspnId($espnAthleteDto->getId());
        $espnAthlete->setUid($espnAthleteDto->getUid());
        $espnAthlete->setGuid($espnAthleteDto->getGuid());
        $espnAthlete->setType($espnAthleteDto->getType());
        $espnAthlete->setAlternateIds($espnAthleteDto->getAlternateIds());
        $espnAthlete->setFirstName($espnAthleteDto->getFirstName());
        $espnAthlete->setLastName($espnAthleteDto->getLastName());
        $espnAthlete->setFullName($espnAthleteDto->getFullName());
        $espnAthlete->setDisplayName($espnAthleteDto->getDisplayName());
        $espnAthlete->setShortName($espnAthleteDto->getShortName());
        $espnAthlete->setWeight($espnAthleteDto->getWeight());
        $espnAthlete->setDisplayWeight($espnAthleteDto->getDisplayWeight());
        $espnAthlete->setHeight($espnAthleteDto->getHeight());
        $espnAthlete->setDisplayHeight($espnAthleteDto->getDisplayHeight());
        $espnAthlete->setSlug($espnAthleteDto->getSlug());
        $espnAthlete->setJersey($espnAthleteDto->getJersey());
        $espnAthlete->setLinked($espnAthleteDto->getLinked());
        $espnAthlete->setActive($espnAthleteDto->getActive());
        $espnAthlete->setAge($espnAthleteDto->getAge());
        $espnAthlete->setDateOfBirth($espnAthleteDto->getDateOfBirth());
        $espnAthlete->setDebutYear($espnAthleteDto->getDebutYear());
        $espnAthlete->setCollegeReference($espnAthleteDto->getCollegeReference());
        $espnAthlete->setCollegeAthleteReference($espnAthleteDto->getCollegeAthleteReference());
        $espnAthlete->setNotesReference($espnAthleteDto->getNotesReference());
        $espnAthlete->setContractsReference($espnAthleteDto->getContractsReference());
        $espnAthlete->setContractReference($espnAthleteDto->getContractReference());
        $espnAthlete->setStatisticsReference($espnAthleteDto->getStatisticsReference());
        $espnAthlete->setProjectionsReference($espnAthleteDto->getProjectionsReference());
        $espnAthlete->setEventLogReference($espnAthleteDto->getEventLogReference());
        $espnAthlete->setInjuriesReferences($espnAthleteDto->getInjuriesReferences());

        // position reference stored for lookup; entity relation connected in the importer
        $espnAthlete->setPositionReference($espnAthleteDto->getPositionReference());

        if (null !== $espnAthleteDto->getHeadshot()) {
            $espnAthlete->setHeadshotHref($espnAthleteDto->getHeadshot()->getHref());
            $espnAthlete->setHeadshotAlt($espnAthleteDto->getHeadshot()->getAlt());
        }

        if (null !== $espnAthleteDto->getDraft()) {
            $draft = $espnAthleteDto->getDraft();
            $espnAthlete->setDraftDisplayText($draft->getDisplayText());
            $espnAthlete->setDraftRound($draft->getRound());
            $espnAthlete->setDraftYear($draft->getYear());
            $espnAthlete->setDraftSelection($draft->getSelection());
            $espnAthlete->setDraftTeamReference($draft->getTeamReference());
        }

        if (null !== $espnAthleteDto->getExperience()) {
            $espnAthlete->setExperienceYears($espnAthleteDto->getExperience()->getYears());
        }

        if (null !== $espnAthleteDto->getBirthPlace()) {
            $espnAthlete->setBirthPlace($this->espnAddressConverter->toEntity($espnAthleteDto->getBirthPlace()));
        }

        if (null !== $espnAthleteDto->getStatus()) {
            $espnAthlete->setStatus($this->espnAthleteStatusConverter->toEntity($espnAthleteDto->getStatus()));
        }

        // position entity is connected in the importer
        // team entity is connected in the importer

        return $espnAthlete;
    }
}
