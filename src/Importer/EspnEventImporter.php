<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnEventConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnEvent;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnWeekRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnEventConverter $converter
 */
class EspnEventImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface                    $espnApiClient,
        ConverterInterface                        $converter,
        private readonly EspnSeasonRepository     $espnSeasonRepository,
        private readonly EspnSeasonTypeRepository $espnSeasonTypeRepository,
        private readonly EspnWeekRepository       $espnWeekRepository,
    )
    {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(
        string $reference,
        int    $seasonId,
        int    $seasonTypeId,
        int    $weekId,
    ): EspnEvent
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_EVENT
        );

        if (null === $urlParams->eventId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve eventId from event reference: %s',
                $reference
            ));
        }

        $espnEventDto = $this->espnApiClient->events()->get($urlParams->eventId);

        if (!$espnEventDto) {
            throw new ImportException(sprintf('Event %d not found', $urlParams->eventId));
        }

        $espnEvent = $this->converter->toEntity($espnEventDto);

        $espnEvent->setSeason($this->espnSeasonRepository->find($seasonId));
        $espnEvent->setSeasonType($this->espnSeasonTypeRepository->find($seasonTypeId));
        $espnEvent->setWeek($this->espnWeekRepository->find($weekId));

        return $espnEvent;
    }
}
