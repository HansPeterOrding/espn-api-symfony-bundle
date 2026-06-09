<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnScoreConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnScore;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitorRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnScoreConverter $converter
 */
class EspnScoreImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface $espnApiClient,
        ConverterInterface $converter,
        private readonly EspnCompetitorRepository $espnCompetitorRepository,
    ) {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference, int $competitorId): EspnScore
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_EVENT_COMPETITION_COMPETITOR_SCORE
        );

        if (null === $urlParams->eventId || null === $urlParams->competitionId || null === $urlParams->competitorId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve required params from score reference: %s',
                $reference
            ));
                    }

        $espnCompetitor = $this->espnCompetitorRepository->find($competitorId);
        if (null === $espnCompetitor) {
            throw new UnrecoverableImportException(sprintf('Competitor with id %d not found.',
                $competitorId
            ));
        }

        $espnScoreDto = $this->espnApiClient->events()->competitions()->competitors()->scores()->get(
            $urlParams->eventId,
            $urlParams->competitionId,
            $urlParams->competitorId
        );

        if (!$espnScoreDto) {
            throw new ImportException(sprintf(
                'Score for competitor %d not found',
                $urlParams->competitorId
            ));
        }

        $espnScore = $this->converter->toEntity($espnScoreDto, $espnCompetitor);
        $espnScore->setCompetitor($espnCompetitor);

        return $espnScore;
    }
}
