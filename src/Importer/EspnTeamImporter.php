<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnNoteConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnTeamConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnFranchiseRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonGroupRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnVenueRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnTeamConverter $converter
 */
class EspnTeamImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface                     $espnApiClient,
        ConverterInterface                         $converter,
        private readonly EspnVenueRepository       $espnVenueRepository,
        private readonly EspnFranchiseRepository   $espnFranchiseRepository,
        private readonly EspnSeasonGroupRepository $espnSeasonGroupRepository,
        private readonly EspnNoteConverter         $espnNoteConverter,
    )
    {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference): EspnTeam
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TEAM
        );

        if (null === $urlParams->year || null === $urlParams->teamId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve year or teamId from team reference: %s',
                $reference
            ));
        }

        $espnTeamDto = $this->espnApiClient->seasons()->teams()->get(
            $urlParams->year,
            $urlParams->teamId
        );

        if (!$espnTeamDto) {
            throw new ImportException(sprintf(
                'Team %d for season %d not found',
                $urlParams->teamId,
                $urlParams->year
            ));
        }

        $espnTeam = $this->converter->toEntity($espnTeamDto);

        $this->connectVenue($espnTeam);
        $this->connectFranchise($espnTeam);
        $this->syncSeasonGroups($espnTeam);
        $this->processNotes($espnTeam);

        return $espnTeam;
    }

    private function connectVenue(EspnTeam $espnTeam): void
    {
        if (null === $espnTeam->getVenueReference()) {
            return;
        }

        $urlParams = EspnUrlPatternResolver::resolveAll(
            $espnTeam->getVenueReference(),
            EspnUrlPatternResolver::URL_PATTERN_VENUE
        );

        if (null === $urlParams->venueId) {
            return;
        }

        $espnVenue = $this->espnVenueRepository->findOneBy(['espnId' => (string)$urlParams->venueId]);
        $espnTeam->setVenue($espnVenue); // null if not yet imported — will be connected on venue import
    }

    private function connectFranchise(EspnTeam $espnTeam): void
    {
        if (null === $espnTeam->getFranchiseReference()) {
            return;
        }

        $urlParams = EspnUrlPatternResolver::resolveAll(
            $espnTeam->getFranchiseReference(),
            EspnUrlPatternResolver::URL_PATTERN_FRANCHISE
        );

        if (null === $urlParams->franchiseId) {
            return;
        }

        $espnFranchise = $this->espnFranchiseRepository->findOneBy(['espnId' => (string)$urlParams->franchiseId]);
        $espnTeam->setFranchise($espnFranchise); // null if not yet imported — will be connected on franchise import
    }

    private function syncSeasonGroups(EspnTeam $espnTeam): void
    {
        if (null === $espnTeam->getGroupsReference()) {
            return;
        }

        $urlParams = EspnUrlPatternResolver::resolveAll(
            $espnTeam->getGroupsReference(),
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_GROUP
        );

        if (null === $urlParams->groupId) {
            return;
        }

        // Clear existing group assignments via owning side
        $espnTeam->removeAllSeasonGroups();

        // Connect the direct group and walk up the parent hierarchy
        $group = $this->espnSeasonGroupRepository->findOneBy(
            ['espnId' => (string)$urlParams->groupId]
        );

        while (null !== $group) {
            $espnTeam->addSeasonGroup($group); // owning side — Doctrine writes the join table
            $group = $group->getParent();
        }
    }

    private function processNotes(EspnTeam $espnTeam): void
    {
        if (null === $espnTeam->getEspnId()) {
            return;
        }

        $noteDtos = $this->espnApiClient->seasons()->teams()->notes()->listForTeam(
            (int)$espnTeam->getEspnId()
        );

        foreach ($noteDtos as $noteDto) {
            $espnNote = $this->espnNoteConverter->toEntity($noteDto, $espnTeam);
            $espnNote->setTeam($espnTeam);
            $espnTeam->addOrReplaceNote($espnNote);
        }
    }
}
