<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnCompetitionStatusConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnCompetitionStatus;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitionRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnCompetitionStatusConverter $converter
 */
class EspnCompetitionStatusImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface $espnApiClient,
        ConverterInterface $converter,
        private readonly EspnCompetitionRepository $espnCompetitionRepository,
    ) {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference): EspnCompetitionStatus
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_EVENT_COMPETITION_STATUS
        );

        if (null === $urlParams->eventId || null === $urlParams->competitionId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve eventId or competitionId from competition status reference: %s',
                $reference
            ));
                    }

        $espnCompetition = $this->espnCompetitionRepository->findOneBy(
            ['espnId' => (string) $urlParams->competitionId]
        );

        if (null === $espnCompetition) {
            throw new UnrecoverableImportException(sprintf('Competition with ESPN id %d not found. Import the competition first.',
                $urlParams->competitionId
            ));
        }

        $espnCompetitionStatusDto = $this->espnApiClient->events()->competitions()->status()->get(
            $urlParams->eventId,
            $urlParams->competitionId
        );

        if (!$espnCompetitionStatusDto) {
            throw new ImportException(sprintf(
                'Competition status for event %d competition %d not found',
                $urlParams->eventId,
                $urlParams->competitionId
            ));
        }

        $espnCompetitionStatus = $this->converter->toEntity($espnCompetitionStatusDto, $espnCompetition);
        $espnCompetitionStatus->setCompetition($espnCompetition);

        return $espnCompetitionStatus;
    }
}
