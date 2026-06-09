<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnCoachImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnCoachMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
class ImportEspnCoachMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnCoachImporter $espnCoachImporter,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnCoachMessage $message): void
    {
        try {
            $espnCoach = $this->espnCoachImporter->buildEntityFromReference(
                $message->reference,
                $message->seasonId,
            );

            $this->entityManager->persist($espnCoach);
            $this->entityManager->flush();
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnCoachMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'seasonId' => $message->seasonId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (\Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnCoachMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'seasonId' => $message->seasonId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw $e;
        }
    }
}
