<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientFactory;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnVenueConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\SyncEspnVenue;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
class SyncEspnVenueHandler {
    public function __construct(
        private readonly EspnVenueConverter     $espnVenueConverter,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $slackDebugLogger
    )
    {
        $this->espnApiClient = (new EspnApiClientFactory())->getEspnApiClient();
    }

    public function __invoke(SyncEspnVenue $message)
    {
        try {
            $espnVenue = $this->espnApiClient->venue()->get($message->espnVenueId);

            if (!$espnVenue) {
                throw new ImportException(sprintf(
                    'Venue with ID %s not found',
                    $message->espnVenueId
                ));
            }

            $espnVenueEntity = $this->espnVenueConverter->toEntity($espnVenue);

            $this->entityManager->persist($espnVenueEntity);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->slackDebugLogger->critical(
                'SyncEspnVenueHandler command error!',
                [
                    'message' => $e->getMessage(),
                    'venueId' => $message->espnVenueId,
                    'previous' => $e->getPrevious()
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage());
        }
    }
}
