<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonGroup;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnSeasonGroupImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnSeasonGroupMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnStandingMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnTeamMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonGroupRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use HansPeterOrding\EspnApiSymfonyBundle\Util\EspnUrlPatternResolver;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ImportEspnSeasonGroupMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnApiClientInterface $espnApiClient,
        private readonly EspnSeasonGroupImporter $espnSeasonGroupImporter,
        private readonly EspnSeasonGroupRepository $espnSeasonGroupRepository,
        private readonly MessageBusInterface $messageBus,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnSeasonGroupMessage $message): void
    {
        try {
            $importEntities = $message->importEntities ?? EspnImportService::getSeasonImportEntities();

            $parentGroup = null;
            if (null !== $message->parentGroupId) {
                $parentGroup = $this->espnSeasonGroupRepository->find($message->parentGroupId);
                if (null === $parentGroup) {
                    throw new UnrecoverableMessageHandlingException(sprintf(
                        'Parent SeasonGroup with id %d not found.',
                        $message->parentGroupId
                    ));
                }
            }

            $espnSeasonGroup = $this->espnSeasonGroupImporter->buildEntityFromReference(
                $message->reference,
                $parentGroup
            );

            $this->entityManager->persist($espnSeasonGroup);
            $this->entityManager->flush();

            $this->dispatchSubsequentMessages($espnSeasonGroup, $message->seasonId, $importEntities);
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnSeasonGroupMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'seasonId' => $message->seasonId,
                    'parentGroupId' => $message->parentGroupId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (\Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnSeasonGroupMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'seasonId' => $message->seasonId,
                    'parentGroupId' => $message->parentGroupId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw $e;
        }
    }

    private function dispatchSubsequentMessages(
        EspnSeasonGroup $espnSeasonGroup,
        int $seasonId,
        array $importEntities
    ): void {
        // Dispatch child group messages — recursion terminates naturally at leaf groups
        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_SEASON_GROUPS)
            && null !== $espnSeasonGroup->getChildrenReference()
        ) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $espnSeasonGroup->getChildrenReference(),
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_GROUP_CHILDREN
            );

            $childGroupReferences = $this->espnApiClient->seasons()->seasonTypes()->seasonGroups()->listChildRefs(
                $urlParams->year,
                $urlParams->typeId,
                $urlParams->groupId
            );

            foreach ($childGroupReferences as $childGroupReference) {
                $this->messageBus->dispatch(new ImportEspnSeasonGroupMessage(
                    reference: $childGroupReference,
                    seasonId: $seasonId,
                    parentGroupId: $espnSeasonGroup->getId(),
                    importEntities: $importEntities,
                ));
            }
        }

        if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_STANDINGS)
            && null !== $espnSeasonGroup->getStandingsReference()
        ) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $espnSeasonGroup->getStandingsReference(),
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_GROUP_STANDINGS
            );

            $standingReferences = $this->espnApiClient->seasons()->seasonTypes()->seasonGroups()->standings()->listRefs(
                $urlParams->year,
                $urlParams->typeId,
                $urlParams->groupId
            );

            foreach ($standingReferences as $standingReference) {
                $this->messageBus->dispatch(new ImportEspnStandingMessage(
                    reference: $standingReference,
                    seasonGroupId: $espnSeasonGroup->getId(),
                    seasonId: $seasonId,
                    importEntities: $importEntities,
                ));
            }
        }

        if ($this->shouldImportTeamsForGroup($importEntities, $espnSeasonGroup->getIsConference() ?? false)) {
            $urlParams = EspnUrlPatternResolver::resolveAll(
                $espnSeasonGroup->getTeamsReference()
                    ?? $espnSeasonGroup->getStandingsReference()
                    ?? '',
                EspnUrlPatternResolver::URL_PATTERN_SEASON_TYPE_GROUP
            );

            $teamReferences = $this->espnApiClient->seasons()->teams()->listRefsForSeasonGroup(
                $urlParams->year,
                $urlParams->typeId,
                $urlParams->groupId
            );

            foreach ($teamReferences as $teamReference) {
                $this->messageBus->dispatch(new ImportEspnTeamMessage(
                    reference: $teamReference,
                    seasonId: $seasonId,
                    importEntities: $importEntities,
                ));
            }
        }
    }
}
