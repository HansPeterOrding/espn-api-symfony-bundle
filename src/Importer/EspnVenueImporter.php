<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnVenueConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenue;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnFranchiseRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnVenueConverter $converter
 */
class EspnVenueImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface $espnApiClient,
        ConverterInterface $converter,
        private readonly EspnTeamRepository $espnTeamRepository,
        private readonly EspnFranchiseRepository $espnFranchiseRepository,
    ) {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference): EspnVenue
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_VENUE
        );

        if (null === $urlParams->venueId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve venueId from venue reference: %s',
                $reference
            ));
                    }

        $espnVenueDto = $this->espnApiClient->venues()->get($urlParams->venueId);

        if (!$espnVenueDto) {
            throw new ImportException(sprintf(
                'Venue %d not found',
                $urlParams->venueId
            ));
        }

        $espnVenue = $this->converter->toEntity($espnVenueDto);

        $this->connectTeams($espnVenue, $reference);
        $this->connectFranchises($espnVenue, $reference);

        return $espnVenue;
    }

    private function connectTeams(EspnVenue $espnVenue, string $reference): void
    {
        $teams = $this->espnTeamRepository->findBy(['venueReference' => $reference]);
        foreach ($teams as $team) {
            $espnVenue->addOrReplaceTeam($team);
        }
    }

    private function connectFranchises(EspnVenue $espnVenue, string $reference): void
    {
        $franchises = $this->espnFranchiseRepository->findBy(['venueReference' => $reference]);
        foreach ($franchises as $franchise) {
            $espnVenue->addOrReplaceFranchise($franchise);
        }
    }
}
