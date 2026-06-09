<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnPositionMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnPositionsMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ImportEspnPositionsMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnApiClientInterface $espnApiClient,
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnPositionsMessage $message): void
    {
        try {
            $importEntities = $message->importEntities ?? EspnImportService::getPositionsImportEntities();

            if (!$this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_POSITION)) {
                return;
            }

            $positionReferences = $this->espnApiClient->positions()->listRefs();

            foreach ($positionReferences as $positionReference) {
                $this->messageBus->dispatch(new ImportEspnPositionMessage(
                    reference: $positionReference,
                    importEntities: $importEntities,
                ));
            }
        } catch (\Throwable $e) {
            $this->importLogger->critical(
                'ImportEspnPositionsMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        }
    }
}
