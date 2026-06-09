<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnRecordMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnStandingMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonGroupRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ImportEspnStandingMessageHandler
{
    use ImportEntitiesHelperTrait;

    // ESPN standing type indices
    private const STANDING_TYPE_OVERALL = 0;
    private const STANDING_TYPE_PLAYOFF = 1;
    private const STANDING_TYPE_EXPANDED = 2;
    private const STANDING_TYPE_DIVISION = 3;

    private const STANDING_TYPE_MAP = [
        EspnImportService::IMPORT_STANDINGS_TYPE_OVERALL => self::STANDING_TYPE_OVERALL,
        EspnImportService::IMPORT_STANDINGS_TYPE_PLAYOFF => self::STANDING_TYPE_PLAYOFF,
        EspnImportService::IMPORT_STANDINGS_TYPE_EXPANDED => self::STANDING_TYPE_EXPANDED,
        EspnImportService::IMPORT_STANDINGS_TYPE_DIVISION => self::STANDING_TYPE_DIVISION,
    ];

    public function __construct(
        private readonly EspnApiClientInterface $espnApiClient,
        private readonly EspnSeasonGroupRepository $espnSeasonGroupRepository,
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnStandingMessage $message): void
    {
        try {
            $importEntities = $message->importEntities ?? EspnImportService::getSeasonImportEntities();

            $espnSeasonGroup = $this->espnSeasonGroupRepository->find($message->seasonGroupId);
            if (null === $espnSeasonGroup) {
                throw new UnrecoverableMessageHandlingException(sprintf(
                    'SeasonGroup with id %d not found.',
                    $message->seasonGroupId
                ));
            }

            if (!$this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_STANDINGS)) {
                return;
            }

            $standingsConfig = $importEntities[EspnImportService::IMPORT_ENTITY_STANDINGS];
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $espnSeasonGroup->getStandingsReference() ?? '',
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_GROUP_STANDINGS
            );

            if (null === $urlParams->year || null === $urlParams->typeId || null === $urlParams->groupId) {
                throw new UnrecoverableMessageHandlingException(sprintf(
                    'Could not resolve standings URL params for season group %d',
                    $message->seasonGroupId
                ));
            }

            foreach (self::STANDING_TYPE_MAP as $configKey => $standingIndex) {
                if (!$this->shouldImportStandingType($standingsConfig, $configKey)) {
                    continue;
                }

                $standingData = $this->espnApiClient->seasons()->seasonTypes()->seasonGroups()->standings()->getAsArray(
                    $urlParams->year,
                    $urlParams->typeId,
                    $urlParams->groupId,
                    $standingIndex
                );

                if (empty($standingData['standings'])) {
                    continue;
                }

                foreach ($standingData['standings'] as $teamEntry) {
                    if (empty($teamEntry['records'])) {
                        continue;
                    }

                    foreach ($teamEntry['records'] as $record) {
                        $recordRef = $record['$ref'] ?? null;
                        if (null === $recordRef) {
                            continue;
                        }

                        $this->messageBus->dispatch(new ImportEspnRecordMessage(
                            reference: $recordRef,
                            importEntities: $importEntities,
                        ));
                    }
                }
            }
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnStandingMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'seasonGroupId' => $message->seasonGroupId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (\Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnStandingMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'seasonGroupId' => $message->seasonGroupId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw $e;
        }
    }

    private function shouldImportStandingType(mixed $config, string $typeKey): bool
    {
        if ($config === true) {
            return true;
        }

        if (is_array($config)) {
            return $config[$typeKey] ?? false;
        }

        return false;
    }
}
