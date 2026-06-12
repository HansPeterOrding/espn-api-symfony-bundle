<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Importer\EspnPositionImporter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\ImportEspnPositionMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\UnrecoverableImportException;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Throwable;

#[AsMessageHandler]
class ImportEspnPositionMessageHandler
{
    use ImportEntitiesHelperTrait;

    public function __construct(
        private readonly EspnPositionImporter   $espnPositionImporter,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $importLogger,
    )
    {
    }

    public function __invoke(ImportEspnPositionMessage $message): void
    {
        try {
            $espnPosition = $this->espnPositionImporter->buildEntityFromReference($message->reference);

            $this->entityManager->persist($espnPosition);

            // Persist parent inline if it was created fresh
            if (null !== $espnPosition->getParent() && null === $espnPosition->getParent()->getId()) {
                $this->entityManager->persist($espnPosition->getParent());
            }

            $this->entityManager->flush();
        } catch (UnrecoverableImportException $e) {
            $this->importLogger->critical(
                'ImportEspnPositionMessageHandler error',
                [
                    'message' => $e->getMessage(),
                    'reference' => $message->reference,
                    'previous' => $e->getPrevious()?->getMessage(),
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage(), previous: $e);
        } catch (Throwable $e) {
            $this->importLogger->warning(
                'ImportEspnPositionMessageHandler error',
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
