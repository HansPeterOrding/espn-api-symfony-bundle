<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnRecordConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnRecord;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnRecordConverter $converter
 */
class EspnRecordImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface $espnApiClient,
        ConverterInterface $converter,
        private readonly EspnTeamRepository $espnTeamRepository,
        private readonly EspnSeasonRepository $espnSeasonRepository,
        private readonly EspnSeasonTypeRepository $espnSeasonTypeRepository,
    ) {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference): EspnRecord
    {
        // Try team-scoped pattern first, then group-scoped pattern
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_TEAM_RECORD
        );

        if (null === $urlParams->recordId) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $reference,
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_GROUP_TEAM_RECORD
            );
        }

        if (null === $urlParams->year || null === $urlParams->typeId
            || null === $urlParams->teamId || null === $urlParams->recordId
        ) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve required params from record reference: %s',
                $reference
            ));
                    }

        $espnRecordDto = null !== $urlParams->groupId
            ? $this->espnApiClient->seasons()->teams()->records()->getForGroup(
                $urlParams->year,
                $urlParams->typeId,
                $urlParams->groupId,
                $urlParams->teamId,
                $urlParams->recordId
            )
            : $this->espnApiClient->seasons()->teams()->records()->get(
                $urlParams->year,
                $urlParams->typeId,
                $urlParams->teamId,
                $urlParams->recordId
            );

        if (!$espnRecordDto) {
            throw new ImportException(sprintf(
                'Record %d for team %d type %d season %d not found',
                $urlParams->recordId,
                $urlParams->teamId,
                $urlParams->typeId,
                $urlParams->year
            ));
        }

        $espnTeam = $this->espnTeamRepository->findOneBy(['espnId' => (string) $urlParams->teamId]);
        if (null === $espnTeam) {
            throw new UnrecoverableImportException(sprintf('Team with ESPN id %d not found. Import the team first.',
                $urlParams->teamId
            ));
        }

        $espnSeason = $this->espnSeasonRepository->findOneBy(['espnYear' => $urlParams->year]);
        if (null === $espnSeason) {
            throw new UnrecoverableImportException(sprintf(
                'Season %d not found. Import the season first.',
                $urlParams->year
            ));
        }

        $espnSeasonType = $this->espnSeasonTypeRepository->findOneBy([
            'espnId' => (string) $urlParams->typeId,
            'season' => $espnSeason,
        ]);
        if (null === $espnSeasonType) {
            throw new UnrecoverableImportException(sprintf(
                'SeasonType with ESPN id %d for season %d not found. Import the season type first.',
                $urlParams->typeId,
                $urlParams->year
            ));
        }

        return $this->converter->toEntity($espnRecordDto, $espnTeam, $espnSeasonType, $espnSeason);
    }
}
