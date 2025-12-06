<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientFactory;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnTeamConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnVenueConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\SyncEspnTeam;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\SyncEspnVenue;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
class SyncEspnTeamHandler {
    private EspnApiClientInterface $espnApiClient;

    public function __construct(
        private readonly EspnTeamConverter      $espnTeamConverter,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $slackDebugLogger
    )
    {
        $this->espnApiClient = (new EspnApiClientFactory())->getEspnApiClient();
    }

    public function __invoke(SyncEspnTeam $message)
    {
        try {
            $espnTeam = $this->espnApiClient->team()->get($message->espnTeamId);

            if (!$espnTeam) {
                throw new ImportException(sprintf(
                    'Team with ID %s not found',
                    $message->espnTeamId
                ));
            }

            $espnTeamEntity = $this->espnTeamConverter->toEntity($espnTeam);

            $this->entityManager->persist($espnTeamEntity);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->slackDebugLogger->critical(
                'SyncEspnTeamHandler command error!',
                [
                    'message' => $e->getMessage(),
                    'venueId' => $message->espnTeamId,
                    'previous' => $e->getPrevious()
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage());
        }
    }
}
