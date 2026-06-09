<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnAthleteImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnAthleteMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnContractMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnInjuryMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnInjuryRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ImportEspnAthleteMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnAthleteImporter $espnAthleteImporter,
        private readonly EspnInjuryRepository $espnInjuryRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnAthleteMessage $message): void
    {
        try {
            $importEntities = $message->importEntities ?? EspnImportService::getSeasonImportEntities();

            $espnAthlete = $this->espnAthleteImporter->buildEntityFromReference($message->reference);

            $this->entityManager->persist($espnAthlete);
            $this->entityManager->flush();

            // Dispatch contract message after flush so athlete has an id
            if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_CONTRACT)
                && null !== $espnAthlete->getContractReference()
            ) {
                $this->messageBus->dispatch(new ImportEspnContractMessage(
                    reference: $espnAthlete->getContractReference(),
                    athleteId: $espnAthlete->getId(),
                    importEntities: $importEntities,
                ));
            }

            // Dispatch injury messages after flush so athlete has an id
            if ($this->shouldImport($importEntities, EspnImportService::IMPORT_ENTITY_INJURIES)
                && count($espnAthlete->getInjuriesReferences()) > 0
            ) {
                // Delete existing injuries — only current injuries should be stored
                $this->espnInjuryRepository->deleteByAthlete($espnAthlete);

                foreach ($espnAthlete->getInjuriesReferences() as $injuryReference) {
                    $this->messageBus->dispatch(new ImportEspnInjuryMessage(
                        reference: $injuryReference,
                        importEntities: $importEntities,
                    ));
                }
            }
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnAthleteMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (\Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnAthleteMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw $e;
        }
    }
}
