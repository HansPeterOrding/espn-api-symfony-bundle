<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnAthleteConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnNoteConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnAthlete;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnPositionRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnAthleteConverter $converter
 */
class EspnAthleteImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface $espnApiClient,
        ConverterInterface $converter,
        private readonly EspnSeasonRepository $espnSeasonRepository,
        private readonly EspnTeamRepository $espnTeamRepository,
        private readonly EspnPositionRepository $espnPositionRepository,
        private readonly EspnNoteConverter $espnNoteConverter,
    ) {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference): EspnAthlete
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_ATHLETE
        );

        if (null === $urlParams->year || null === $urlParams->athleteId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve year or athleteId from athlete reference: %s',
                $reference
            ));
                    }

        $espnSeason = $this->espnSeasonRepository->findOneBy(['espnYear' => $urlParams->year]);
        if (null === $espnSeason) {
            throw new UnrecoverableImportException(sprintf(
                'Season %d not found. Import the season first.',
                $urlParams->year
            ));
        }

        $espnAthleteDto = $this->espnApiClient->seasons()->athletes()->get(
            $urlParams->year,
            $urlParams->athleteId
        );

        if (!$espnAthleteDto) {
            throw new ImportException(sprintf(
                'Athlete %d for season %d not found',
                $urlParams->athleteId,
                $urlParams->year
            ));
        }

        $espnAthlete = $this->converter->toEntity($espnAthleteDto, $espnSeason);

        $espnAthlete->setSeason($espnSeason);
        $this->connectTeam($espnAthlete, $espnAthleteDto->getTeamReference());
        $this->connectPosition($espnAthlete);
        $this->processNotes($espnAthlete, $urlParams->year, $urlParams->athleteId);

        return $espnAthlete;
    }

    private function connectTeam(EspnAthlete $espnAthlete, ?string $teamReference): void
    {
        if (null === $teamReference) {
            $espnAthlete->setTeam(null);
            return;
        }

        $urlParams = EspnUrlPatternResolver::resolveAll(
            $teamReference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TEAM
        );

        if (null === $urlParams->teamId) {
            return;
        }

        $espnTeam = $this->espnTeamRepository->findOneBy(['espnId' => (string) $urlParams->teamId]);
        $espnAthlete->setTeam($espnTeam); // null if not yet imported
    }

    private function connectPosition(EspnAthlete $espnAthlete): void
    {
        if (null === $espnAthlete->getPositionReference()) {
            $espnAthlete->setPosition(null);
            return;
        }

        $urlParams = EspnUrlPatternResolver::resolveAll(
            $espnAthlete->getPositionReference(),
            EspnUrlPatternResolver::URL_PATTERN_POSITION
        );

        if (null === $urlParams->positionId) {
            return;
        }

        $espnPosition = $this->espnPositionRepository->findOneBy(
            ['espnId' => (string) $urlParams->positionId]
        );

        $espnAthlete->setPosition($espnPosition); // null if positions not yet imported
    }

    private function processNotes(EspnAthlete $espnAthlete, int $year, int $athleteId): void
    {
        $noteDtos = $this->espnApiClient->seasons()->athletes()->notes()->listForAthlete($year, $athleteId);

        foreach ($noteDtos as $noteDto) {
            $espnNote = $this->espnNoteConverter->toEntity($noteDto, $espnAthlete);
            $espnNote->setAthlete($espnAthlete);
            $espnAthlete->addOrReplaceNote($espnNote);
        }
    }
}
