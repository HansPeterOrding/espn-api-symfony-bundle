<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Importer;

use Doctrine\ORM\EntityManagerInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\ConverterInterface;
use HansPeterOrding\EspnApiSymfonyBundle\Converter\EspnScheduleConverter;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSchedule;
use HansPeterOrding\EspnApiSymfonyBundle\Exception\ImportException;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;

/**
 * @property EspnScheduleConverter $converter
 */
class EspnScheduleImporter extends AbstractImporter
{
    public function __construct(
        private readonly EspnTeamRepository $teamRepository,
        ConverterInterface $converter,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($converter, $entityManager);
    }

    public function import(string $teamId): EspnSchedule
    {
        $team = $this->teamRepository->findOneBy([
            'teamId' => $teamId
        ]);

        if(!$team) {
            throw new ImportException('No team with the given ID found. You have to import the team first to allow schedule importing.');
        }
        $espnSchedule = $this->espnApiClient->team()->schedule($teamId);

        if(!$espnSchedule) {
            throw new ImportException(sprintf('Schedule for team with teamId %s not found', $teamId));
        }

        $entity = $this->converter->toEntity($team, $espnSchedule);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }
}
