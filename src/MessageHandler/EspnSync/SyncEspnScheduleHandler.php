<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientFactory;
use HansPeterOrding\EspnApiClient\ApiClient\EspnApiClientInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnScheduleConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\SyncEspnSchedule;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
class SyncEspnScheduleHandler {
    private EspnApiClientInterface $espnApiClient;

    public function __construct(
        private readonly EspnTeamRepository     $espnTeamRepository,
        private readonly EspnScheduleConverter  $espnScheduleConverter,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $slackDebugLogger
    )
    {
        $this->espnApiClient = (new EspnApiClientFactory())->getEspnApiClient();
    }

    public function __invoke(SyncEspnSchedule $message)
    {
        try {
            $team = $this->espnTeamRepository->findOneBy([
                'teamId' => $message->espnTeamId
            ]);

            if (!$team) {
                throw new ImportException(sprintf(
                    'Team %s not found in database. Make sure to import all required teams completely before importing a schedule.',
                    $message->espnTeamId
                ));
            }

            $espnSchedule = $this->espnApiClient->team()->schedule($message->espnTeamId);

            if (!$espnSchedule) {
                throw new ImportException(sprintf(
                    'Schedule for team %s not found in ESPN API.',
                    $message->espnTeamId
                ));
            }

            $espnScheduleEntity = $this->espnScheduleConverter->toEntity($team, $espnSchedule);

            $this->entityManager->persist($espnScheduleEntity);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->slackDebugLogger->critical(
                'SyncEspnScheduleHandler command error!',
                [
                    'message' => $e->getMessage(),
                    'teamId' => $message->espnTeamId,
                    'previous' => $e->getPrevious()
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage());
        }
    }
}
