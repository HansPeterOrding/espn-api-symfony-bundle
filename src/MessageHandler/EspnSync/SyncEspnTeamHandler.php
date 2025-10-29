<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnTeamConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnVenueConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\SyncEspnTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\SyncEspnVenue;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
class SyncEspnTeamHandler
{
    public function __construct(
        private readonly EspnTeamConverter $espnTeamConverter,
        private readonly EntityManagerInterface  $entityManager,
        private readonly LoggerInterface         $slackDebugLogger
    ) {
    }

    public function __invoke(SyncEspnTeam $message)
    {
        try {
            $espnTeamEntity = $this->espnTeamConverter->toEntity($message->espnTeam);

            $this->entityManager->persist($espnTeamEntity);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->slackDebugLogger->critical(
                'SyncEspnTeamHandler command error!',
                [
                    'message' => $e->getMessage(),
                    'venueId' => $message->espnTeam->getId(),
                    'previous' => $e->getPrevious()
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage());
        }
    }
}
