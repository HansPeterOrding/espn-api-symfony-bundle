<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnInjuryConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnInjury;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnAthleteRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnInjuryConverter $converter
 */
class EspnInjuryImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface                 $espnApiClient,
        ConverterInterface                     $converter,
        private readonly EspnAthleteRepository $espnAthleteRepository,
        private readonly EspnTeamRepository    $espnTeamRepository,
    )
    {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference): EspnInjury
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_ATHLETE_INJURY
        );

        if (null === $urlParams->year || null === $urlParams->athleteId || null === $urlParams->injuryId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve required params from injury reference: %s',
                $reference
            ));
        }

        $espnInjuryDto = $this->espnApiClient->seasons()->athletes()->injuries()->get(
            $urlParams->year,
            $urlParams->athleteId,
            $urlParams->injuryId
        );

        if (!$espnInjuryDto) {
            throw new ImportException(sprintf(
                'Injury %d for athlete %d season %d not found',
                $urlParams->injuryId,
                $urlParams->athleteId,
                $urlParams->year
            ));
        }

        $espnInjury = $this->converter->toEntity($espnInjuryDto);

        // Connect all athlete entities with matching ESPN id across all seasons
        $espnAthletes = $this->espnAthleteRepository->findBy(['espnId' => (string)$urlParams->athleteId]);
        foreach ($espnAthletes as $espnAthlete) {
            $espnInjury->addAthlete($espnAthlete);
        }

        // Connect team via injury DTO reference
        if (null !== $espnInjuryDto->getTeamReference()) {
            $teamUrlParams = EspnUrlPatternResolver::resolveAll(
                $espnInjuryDto->getTeamReference(),
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TEAM
            );
            if (null !== $teamUrlParams->teamId) {
                $espnTeam = $this->espnTeamRepository->findOneBy(['espnId' => (string)$teamUrlParams->teamId]);
                $espnInjury->setTeam($espnTeam);
            }
        }

        return $espnInjury;
    }
}
