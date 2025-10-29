<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnScheduleConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Message\EspnSync\SyncEspnSchedule;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

#[AsMessageHandler]
class SyncEspnScheduleHandler {
    public function __construct(
        private readonly EspnTeamRepository     $espnTeamRepository,
        private readonly EspnScheduleConverter  $espnScheduleConverter,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface        $slackDebugLogger
    )
    {
    }

    public function __invoke(SyncEspnSchedule $message)
    {
        try {
            $team = $this->espnTeamRepository->findOneBy([
                'teamId' => $message->espnSchedule->getTeam()->getId()
            ]);

            if (!$team) {
                throw new ImportException(sprintf(
                    'Team %s not found in database. Make sure to import all required teams completely before importing a schedule.',
                    $message->espnSchedule->getTeam()->getDisplayName()
                ));
            }

            $espnTeamEntity = $this->espnScheduleConverter->toEntity($team, $message->espnSchedule);

            $this->entityManager->persist($espnTeamEntity);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            $this->slackDebugLogger->critical(
                'SyncEspnScheduleHandler command error!',
                [
                    'message' => $e->getMessage(),
                    'scheduleId' => $message->espnSchedule->getId(),
                    'previous' => $e->getPrevious()
                ]
            );
            throw new UnrecoverableMessageHandlingException($e->getMessage());
        }
    }
}
