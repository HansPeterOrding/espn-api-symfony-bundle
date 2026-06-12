<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnInjuryImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnInjuryMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Throwable;

#[AsMessageHandler]
class ImportEspnInjuryMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnInjuryImporter $espnInjuryImporter,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $importLogger,
    ) {
    }

    public function __invoke(ImportEspnInjuryMessage $message): void
    {
        try {
            $espnInjury = $this->espnInjuryImporter->buildEntityFromReference(
                $message->reference,
            );

            $this->entityManager->persist($espnInjury);
            $this->entityManager->flush();
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnInjuryMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnInjuryMessageHandler error',
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
