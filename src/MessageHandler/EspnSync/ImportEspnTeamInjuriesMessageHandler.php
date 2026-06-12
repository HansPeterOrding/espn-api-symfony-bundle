<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnInjuryMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnTeamInjuriesMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnInjuryRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
class ImportEspnTeamInjuriesMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnApiClientInterface $espnApiClient,
        private readonly EspnTeamRepository     $espnTeamRepository,
        private readonly EspnInjuryRepository   $espnInjuryRepository,
        private readonly MessageBusInterface    $messageBus,
        private readonly LoggerInterface        $importLogger,
    )
    {
    }

    public function __invoke(ImportEspnTeamInjuriesMessage $message): void
    {
        try {
            $importEntities = $message->importEntities ?? EspnImportService::getSeasonImportEntities();

            $espnTeam = $this->espnTeamRepository->find($message->teamId);
            if (null === $espnTeam) {
                throw new UnrecoverableMessageHandlingException(sprintf(
                    'Team with id %d not found.',
                    $message->teamId
                ));
            }

            // Delete all existing injuries for this team — only current injuries should be stored
            $this->espnInjuryRepository->deleteByTeam($espnTeam);

            $injuryReferences = $this->espnApiClient->seasons()->teams()->injuries()->listRefsForTeam(
                (int)$espnTeam->getEspnId()
            );

            foreach ($injuryReferences as $injuryReference) {
                $this->messageBus->dispatch(new ImportEspnInjuryMessage(
                    reference: $injuryReference,
                    importEntities: $importEntities,
                ));
            }
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnTeamInjuriesMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'teamId' => $message->teamId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnTeamInjuriesMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'teamId' => $message->teamId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw $e;
        }
    }
}
