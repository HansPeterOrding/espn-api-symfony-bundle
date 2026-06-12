<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnFranchiseConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnFranchise;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnVenueRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnFranchiseConverter $converter
 */
class EspnFranchiseImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface               $espnApiClient,
        ConverterInterface                   $converter,
        private readonly EspnTeamRepository  $espnTeamRepository,
        private readonly EspnVenueRepository $espnVenueRepository,
    )
    {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference): EspnFranchise
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_FRANCHISE
        );

        if (null === $urlParams->franchiseId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve franchiseId from franchise reference: %s',
                $reference
            ));
        }

        $espnFranchiseDto = $this->espnApiClient->franchises()->get($urlParams->franchiseId);

        if (!$espnFranchiseDto) {
            throw new ImportException(sprintf(
                'Franchise %d not found',
                $urlParams->franchiseId
            ));
        }

        $espnFranchise = $this->converter->toEntity($espnFranchiseDto);

        $this->connectVenue($espnFranchise);
        $this->connectTeam($espnFranchise, $reference);

        return $espnFranchise;
    }

    private function connectVenue(EspnFranchise $espnFranchise): void
    {
        if (null === $espnFranchise->getVenueReference()) {
            return;
        }

        $urlParams = EspnUrlPatternResolver::resolveAll(
            $espnFranchise->getVenueReference(),
            EspnUrlPatternResolver::URL_PATTERN_VENUE
        );

        if (null === $urlParams->venueId) {
            return;
        }

        $espnVenue = $this->espnVenueRepository->findOneBy(['espnId' => (string)$urlParams->venueId]);
        $espnFranchise->setVenue($espnVenue); // null if not yet imported — will connect on venue import
    }

    private function connectTeam(EspnFranchise $espnFranchise, string $reference): void
    {
        $team = $this->espnTeamRepository->findOneBy(['franchiseReference' => $reference]);
        $espnFranchise->setTeam($team); // also sets $team->setFranchise($this) via owning side
    }
}
