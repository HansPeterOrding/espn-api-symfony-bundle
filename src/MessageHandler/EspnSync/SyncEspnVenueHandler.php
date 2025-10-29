<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnVenueConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\SyncEspnVenue;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
class SyncEspnVenueHandler
{
    public function __construct(
        private readonly EspnVenueConverter $espnVenueConverter,
        private readonly EntityManagerInterface  $entityManager,
        private readonly LoggerInterface         $slackDebugLogger
    ) {
    }

    public function __invoke(SyncEspnVenue $message)
    {
        try {
            $espnVenueEntity = $this->espnVenueConverter->toEntity($message->espnVenue);

            $this->entityManager->persist($espnVenueEntity);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->slackDebugLogger->critical(
                'SyncEspnVenueHandler command error!',
                [
                    'message' => $e->getMessage(),
                    'venueId' => $message->espnVenue->getId(),
                    'previous' => $e->getPrevious()
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage());
        }
    }
}
