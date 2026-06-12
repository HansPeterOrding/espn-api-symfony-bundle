<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnContractConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnContract;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnAthleteRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnContractConverter $converter
 */
class EspnContractImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface                 $espnApiClient,
        ConverterInterface                     $converter,
        private readonly EspnAthleteRepository $espnAthleteRepository,
        private readonly EspnTeamRepository    $espnTeamRepository,
        private readonly EspnSeasonRepository  $espnSeasonRepository,
    )
    {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference, int $athleteEntityId): EspnContract
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_ATHLETE_CONTRACT
        );

        if (null === $urlParams->athleteId || null === $urlParams->contractYear) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve athleteId or contractYear from contract reference: %s',
                $reference
            ));
        }

        $espnAthlete = $this->espnAthleteRepository->find($athleteEntityId);
        if (null === $espnAthlete) {
            throw new UnrecoverableImportException(sprintf('Athlete with id %d not found.',
                $athleteEntityId
            ));
        }

        $espnContractDto = $this->espnApiClient->athletes()->contracts()->get(
            $urlParams->athleteId,
            $urlParams->contractYear
        );

        if (!$espnContractDto) {
            throw new ImportException(sprintf(
                'Contract for athlete %d year %d not found',
                $urlParams->athleteId,
                $urlParams->contractYear
            ));
        }

        $espnContract = $this->converter->toEntity($espnContractDto, $espnAthlete);

        $espnContract->setAthlete($espnAthlete);
        $this->connectTeam($espnContract);
        $this->connectSeason($espnContract);

        return $espnContract;
    }

    private function connectTeam(EspnContract $espnContract): void
    {
        if (null === $espnContract->getTeamReference()) {
            return;
        }

        $urlParams = EspnUrlPatternResolver::resolveAll(
            $espnContract->getTeamReference(),
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TEAM
        );

        if (null === $urlParams->teamId) {
            return;
        }

        $espnTeam = $this->espnTeamRepository->findOneBy(['espnId' => (string)$urlParams->teamId]);
        $espnContract->setTeam($espnTeam);
    }

    private function connectSeason(EspnContract $espnContract): void
    {
        if (null === $espnContract->getSeasonReference()) {
            return;
        }

        $urlParams = EspnUrlPatternResolver::resolveAll(
            $espnContract->getSeasonReference(),
            EspnUrlPatternResolver::URL_PATTERN_SEASON
        );

        if (null === $urlParams->year) {
            return;
        }

        $espnSeason = $this->espnSeasonRepository->findOneBy(['espnYear' => $urlParams->year]);
        $espnContract->setSeason($espnSeason);
    }
}
