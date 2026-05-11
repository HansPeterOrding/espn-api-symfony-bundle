<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnSeasonTeamConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;

/**
 * @property EspnSeasonTeamConverter $converter
 */
class EspnSeasonTeamImporter extends AbstractImporter
{
    public function import(int $year, int $teamId): EspnSeasonTeam
    {
        $espnSeasonTeam = $this->espnApiClient->season()->team()->get($year, $teamId);

        if(!$espnSeasonTeam) {
            throw new ImportException(sprintf(
                'Team with teamId %s not found for year %s',
                $teamId,
                $year
            ));
        }

        $entity = $this->converter->toEntity($espnSeasonTeam);

        return $entity;
    }

    public function getSeasonTeamReferences(int $year): array
    {
        return $this->espnApiClient->season()->team()->listRefs($year);
    }
}
