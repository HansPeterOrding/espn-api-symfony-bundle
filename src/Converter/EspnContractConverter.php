<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Converter;

use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnAthlete;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnContract;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnContractRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnContract as EspnContractDto;

class EspnContractConverter implements ConverterInterface
{
    public function __construct(
        private readonly EspnContractRepository $espnContractRepository,
    )
    {
    }

    public function toEntity(EspnContractDto $espnContractDto, EspnAthlete $espnAthlete): EspnContract
    {
        $espnContract = $this->espnContractRepository->findByAthleteOrCreateEntity(
            $espnAthlete,
            $espnContractDto->getSignedThrough()
        );

        $espnContract->setSalary($espnContractDto->getSalary());
        $espnContract->setBonus($espnContractDto->getBonus());
        $espnContract->setSalaryRemaining($espnContractDto->getSalaryRemaining());
        $espnContract->setOptionType($espnContractDto->getOptionType());
        $espnContract->setYearsRemaining($espnContractDto->getYearsRemaining());
        $espnContract->setSignedThrough($espnContractDto->getSignedThrough());
        $espnContract->setActive($espnContractDto->getActive());
        $espnContract->setSeasonReference($espnContractDto->getSeasonReference());
        $espnContract->setTeamReference($espnContractDto->getTeamReference());

        // athlete, team and season entity relations connected in the importer

        return $espnContract;
    }
}
