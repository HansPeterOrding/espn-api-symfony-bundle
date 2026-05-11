<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonTypeTeamRecordConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonType;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeTeamRecord;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnSeasonTypeTeamRecordConverter $converter
 */
class EspnSeasonTypeTeamRecordImporter extends AbstractImporter
{
    public function importForType(int $year, EspnSeasonType $espnSeasonType, EspnSeasonTeam $espnSeasonTeam): iterable
    {
        $espnSeasonTypeTeamRecordReferences = $this->espnApiClient->season()->type()->team()->record()->listRefs(
            $year,
            $espnSeasonType->getType(),
            (int)$espnSeasonTeam->getEspnId()
        );

        foreach($espnSeasonTypeTeamRecordReferences as $espnSeasonTypeTeamRecordReference) {
            yield $this->buildEntityFromReference($espnSeasonType, $espnSeasonTeam, $espnSeasonTypeTeamRecordReference);
        }
    }

    private function buildEntityFromReference(EspnSeasonType $espnSeasonType, EspnSeasonTeam $espnSeasonTeam, string $espnSeasonTypeTeamRecordReference): EspnSeasonTypeTeamRecord
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $espnSeasonTypeTeamRecordReference,
            EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_TEAM_RECORD
        );

        $espnSeasonTypeTeamRecord = $this->espnApiClient->season()->type()->team()->record()->get(
            $urlParams->year,
            $urlParams->typeId,
            $urlParams->teamId,
            $urlParams->recordId
        );

        if (!$espnSeasonTypeTeamRecord) {
            throw new ImportException(sprintf(
                'Record %s not found for season type %s, team %s and season %s',
                $urlParams->recordId,
                $urlParams->typeId,
                $urlParams->teamId,
                $urlParams->year
            ));
        }

        return $this->converter->toEntity($espnSeasonType, $espnSeasonTeam, $espnSeasonTypeTeamRecord);
    }
}
