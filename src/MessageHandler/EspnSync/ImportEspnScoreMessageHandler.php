<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnScoreImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnScoreMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
class ImportEspnScoreMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnScoreImporter $espnScoreImporter,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnScoreMessage $message): void
    {
        try {
            $espnScore = $this->espnScoreImporter->buildEntityFromReference(
                $message->reference,
                $message->competitorId,
            );

            $this->entityManager->persist($espnScore);
            $this->entityManager->flush();
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnScoreMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'competitorId' => $message->competitorId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (\Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnScoreMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'competitorId' => $message->competitorId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw $e;
        }
    }
}
