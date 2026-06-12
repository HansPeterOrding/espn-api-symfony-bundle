<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnCompetitionConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetition;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnEventRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnVenueRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnCompetitionConverter $converter
 */
class EspnCompetitionImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface               $espnApiClient,
        ConverterInterface                   $converter,
        private readonly EspnEventRepository $espnEventRepository,
        private readonly EspnVenueRepository $espnVenueRepository,
    )
    {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference, int $eventId): EspnCompetition
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_EVENT_COMPETITION
        );

        if (null === $urlParams->eventId || null === $urlParams->competitionId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve eventId or competitionId from competition reference: %s',
                $reference
            ));
        }

        $espnCompetitionDto = $this->espnApiClient->events()->competitions()->get(
            $urlParams->eventId,
            $urlParams->competitionId
        );

        if (!$espnCompetitionDto) {
            throw new ImportException(sprintf(
                'Competition %d for event %d not found',
                $urlParams->competitionId,
                $urlParams->eventId
            ));
        }

        $espnCompetition = $this->converter->toEntity($espnCompetitionDto);

        $espnEvent = $this->espnEventRepository->find($eventId);
        $espnCompetition->setEvent($espnEvent);

        $this->connectVenue($espnCompetition, $espnCompetitionDto->getVenueReference());

        return $espnCompetition;
    }

    private function connectVenue(EspnCompetition $espnCompetition, ?string $venueReference): void
    {
        if (null === $venueReference) {
            return;
        }

        $urlParams = EspnUrlPatternResolver::resolveAll(
            $venueReference,
            EspnUrlPatternResolver::URL_PATTERN_VENUE
        );

        if (null === $urlParams->venueId) {
            return;
        }

        $espnVenue = $this->espnVenueRepository->findOneBy(['espnId' => (string)$urlParams->venueId]);
        $espnCompetition->setVenue($espnVenue);
    }
}
