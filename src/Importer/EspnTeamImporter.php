<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnTeamConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;

/**
 * @property EspnTeamConverter $converter
 */
class EspnTeamImporter extends AbstractImporter
{
    public function import(string $teamId): EspnTeam
    {
        $espnTeam = $this->espnApiClient->team()->get($teamId);

        if(!$espnTeam) {
            throw new ImportException(sprintf('Team with teamId %s not found', $teamId));
        }

        $entity = $this->converter->toEntity($espnTeam);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }
}
