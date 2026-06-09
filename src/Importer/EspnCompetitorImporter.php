<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnCompetitorConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitor;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitionRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnCompetitorConverter $converter
 */
class EspnCompetitorImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface $espnApiClient,
        ConverterInterface $converter,
        private readonly EspnCompetitionRepository $espnCompetitionRepository,
        private readonly EspnTeamRepository $espnTeamRepository,
    ) {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference, int $competitionId): EspnCompetitor
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_EVENT_COMPETITION_COMPETITOR
        );

        if (null === $urlParams->eventId || null === $urlParams->competitionId || null === $urlParams->competitorId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve required params from competitor reference: %s',
                $reference
            ));
                    }

        $espnCompetition = $this->espnCompetitionRepository->find($competitionId);
        if (null === $espnCompetition) {
            throw new UnrecoverableImportException(sprintf('Competition with id %d not found.',
                $competitionId
            ));
        }

        $espnCompetitorDto = $this->espnApiClient->events()->competitions()->competitors()->get(
            $urlParams->eventId,
            $urlParams->competitionId,
            $urlParams->competitorId
        );

        if (!$espnCompetitorDto) {
            throw new ImportException(sprintf(
                'Competitor %d not found',
                $urlParams->competitorId
            ));
        }

        $espnCompetitor = $this->converter->toEntity($espnCompetitorDto, $espnCompetition);

        $this->connectTeam($espnCompetitor, $espnCompetitorDto->getTeamReference());

        return $espnCompetitor;
    }

    private function connectTeam(EspnCompetitor $espnCompetitor, ?string $teamReference): void
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
        $espnCompetitor->setTeam($espnTeam);
    }
}
