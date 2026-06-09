<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnOfficialConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnOfficial;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitionRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnOfficialConverter $converter
 */
class EspnOfficialImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface $espnApiClient,
        ConverterInterface $converter,
        private readonly EspnCompetitionRepository $espnCompetitionRepository,
    ) {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference, int $competitionId): EspnOfficial
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_EVENT_COMPETITION_OFFICIAL
        );

        if (null === $urlParams->eventId || null === $urlParams->competitionId || null === $urlParams->officialId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve required params from official reference: %s',
                $reference
            ));
                    }

        $espnCompetition = $this->espnCompetitionRepository->find($competitionId);
        if (null === $espnCompetition) {
            throw new UnrecoverableImportException(sprintf('Competition with id %d not found.',
                $competitionId
            ));
        }

        $espnOfficialDto = $this->espnApiClient->events()->competitions()->officials()->get(
            $urlParams->eventId,
            $urlParams->competitionId,
            $urlParams->officialId
        );

        if (!$espnOfficialDto) {
            throw new ImportException(sprintf(
                'Official %d not found',
                $urlParams->officialId
            ));
        }

        $espnOfficial = $this->converter->toEntity($espnOfficialDto, $espnCompetition);
        $espnOfficial->setCompetition($espnCompetition);

        return $espnOfficial;
    }
}
