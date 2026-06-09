<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnCoachConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCoach;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnCoachConverter $converter
 */
class EspnCoachImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface $espnApiClient,
        ConverterInterface $converter,
        private readonly EspnSeasonRepository $espnSeasonRepository,
        private readonly EspnTeamRepository $espnTeamRepository,
    ) {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference, int $seasonId): EspnCoach
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_COACH
        );

        if (null === $urlParams->year || null === $urlParams->coachId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve year or coachId from coach reference: %s',
                $reference
            ));
                    }

        $espnSeason = $this->espnSeasonRepository->find($seasonId);
        if (null === $espnSeason) {
            throw new UnrecoverableImportException(sprintf('Season with id %d not found.', $seasonId));
        }

        $espnCoachDto = $this->espnApiClient->seasons()->coaches()->get(
            $urlParams->year,
            $urlParams->coachId
        );

        if (!$espnCoachDto) {
            throw new ImportException(sprintf(
                'Coach %d for season %d not found',
                $urlParams->coachId,
                $urlParams->year
            ));
        }

        $espnCoach = $this->converter->toEntity($espnCoachDto, $espnSeason);

        $espnCoach->setSeason($espnSeason);
        $this->connectTeam($espnCoach, $espnCoachDto->getTeamReference());

        return $espnCoach;
    }

    private function connectTeam(EspnCoach $espnCoach, ?string $teamReference): void
    {
        if (null === $teamReference) {
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
        $espnCoach->setTeam($espnTeam);
    }
}
