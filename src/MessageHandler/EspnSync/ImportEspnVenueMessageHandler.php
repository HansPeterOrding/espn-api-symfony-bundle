<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnVenueImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnVenueMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Throwable;

#[AsMessageHandler]
class ImportEspnVenueMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnVenueImporter      $espnVenueImporter,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $importLogger,
    )
    {
    }

    public function __invoke(ImportEspnVenueMessage $message): void
    {
        try {
            $espnVenue = $this->espnVenueImporter->buildEntityFromReference($message->reference);

            $this->entityManager->persist($espnVenue);
            $this->entityManager->flush();
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnVenueMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnVenueMessageHandler error',
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
