<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnOfficialImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnOfficialMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Throwable;

#[AsMessageHandler]
class ImportEspnOfficialMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnOfficialImporter   $espnOfficialImporter,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $importLogger,
    )
    {
    }

    public function __invoke(ImportEspnOfficialMessage $message): void
    {
        try {
            $espnOfficial = $this->espnOfficialImporter->buildEntityFromReference(
                $message->reference,
                $message->competitionId,
            );

            $this->entityManager->persist($espnOfficial);
            $this->entityManager->flush();
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnOfficialMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'competitionId' => $message->competitionId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnOfficialMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'competitionId' => $message->competitionId,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw $e;
        }
    }
}
