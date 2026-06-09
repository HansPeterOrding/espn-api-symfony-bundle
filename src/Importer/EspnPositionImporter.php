<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnPositionConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnPosition;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnPositionRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;

/**
 * @property EspnPositionConverter $converter
 */
class EspnPositionImporter extends AbstractImporter
{
    public function __construct(
        EspnApiClientInterface $espnApiClient,
        ConverterInterface $converter,
        private readonly EspnPositionRepository $espnPositionRepository,
    ) {
        parent::__construct($espnApiClient, $converter);
    }

    public function buildEntityFromReference(string $reference): EspnPosition
    {
        $urlParams = EspnUrlPatternResolver::resolveAll(
            $reference,
            EspnUrlPatternResolver::URL_PATTERN_POSITION
        );

        if (null === $urlParams->positionId) {
            throw new UnrecoverableImportException(sprintf(
                'Could not resolve positionId from position reference: %s',
                $reference
            ));
                    }

        $espnPositionDto = $this->espnApiClient->positions()->get($urlParams->positionId);

        if (!$espnPositionDto) {
            throw new ImportException(sprintf(
                'Position %d not found',
                $urlParams->positionId
            ));
        }

        $espnPosition = $this->converter->toEntity($espnPositionDto);

        $this->connectParent($espnPosition, $espnPositionDto->getParentReference());

        return $espnPosition;
    }

    private function connectParent(EspnPosition $espnPosition, ?string $parentReference): void
    {
        if (null === $parentReference) {
            $espnPosition->setParent(null);
            return;
        }

        $urlParams = EspnUrlPatternResolver::resolveAll(
            $parentReference,
            EspnUrlPatternResolver::URL_PATTERN_POSITION
        );

        if (null === $urlParams->positionId) {
            return;
        }

        // Look up existing parent first
        $parent = $this->espnPositionRepository->findOneBy(['espnId' => (string) $urlParams->positionId]);

        if (null === $parent) {
            // Parent not yet imported — fetch and convert inline
            $parentDto = $this->espnApiClient->positions()->get($urlParams->positionId);
            if (null !== $parentDto) {
                // Parent positions have no parent themselves (hierarchy is max 2 levels deep)
                $parent = $this->converter->toEntity($parentDto);
                $parent->setParent(null);
            }
        }

        $espnPosition->setParent($parent);
    }
}
