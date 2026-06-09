<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnRecordImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnRecordMessage;
use HansPeterOrding\EspnApiSymfonyBundle\Service\EspnImportService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
class ImportEspnRecordMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnRecordImporter $espnRecordImporter,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnRecordMessage $message): void
    {
        try {
            $espnRecord = $this->espnRecordImporter->buildEntityFromReference($message->reference);

            $this->entityManager->persist($espnRecord);
            $this->entityManager->flush();
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnRecordMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (\Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnRecordMessageHandler error',
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
