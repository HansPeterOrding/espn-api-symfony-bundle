<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnContractRepository;

#[ORM\Entity(repositoryClass: EspnContractRepository::class)]
#[ORM\Table(name: 'easb_espn_contract')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_espn_contract', columns: ['athlete_id', 'signed_through'])]
class EspnContract
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2, nullable: true)]
    private ?string $salary = null;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2, nullable: true)]
    private ?string $bonus = null;

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2, nullable: true)]
    private ?string $salaryRemaining = null;

    #[ORM\Column(nullable: true)]
    private ?string $optionType = null;

    #[ORM\Column(nullable: true)]
    private ?int $yearsRemaining = null;

    #[ORM\Column(nullable: true)]
    private ?int $signedThrough = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(nullable: true)]
    private ?string $seasonReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $teamReference = null;

    #[ORM\ManyToOne(targetEntity: EspnAthlete::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnAthlete $athlete = null;

    #[ORM\ManyToOne(targetEntity: EspnTeam::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnTeam $team = null;

    #[ORM\ManyToOne(targetEntity: EspnSeason::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnSeason $season = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(?string $salary): static
    {
        $this->salary = $salary;
        return $this;
    }

    public function getBonus(): ?string
    {
        return $this->bonus;
    }

    public function setBonus(?string $bonus): static
    {
        $this->bonus = $bonus;
        return $this;
    }

    public function getSalaryRemaining(): ?string
    {
        return $this->salaryRemaining;
    }

    public function setSalaryRemaining(?string $salaryRemaining): static
    {
        $this->salaryRemaining = $salaryRemaining;
        return $this;
    }

    public function getOptionType(): ?string
    {
        return $this->optionType;
    }

    public function setOptionType(?string $optionType): static
    {
        $this->optionType = $optionType;
        return $this;
    }

    public function getYearsRemaining(): ?int
    {
        return $this->yearsRemaining;
    }

    public function setYearsRemaining(?int $yearsRemaining): static
    {
        $this->yearsRemaining = $yearsRemaining;
        return $this;
    }

    public function getSignedThrough(): ?int
    {
        return $this->signedThrough;
    }

    public function setSignedThrough(?int $signedThrough): static
    {
        $this->signedThrough = $signedThrough;
        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function getSeasonReference(): ?string
    {
        return $this->seasonReference;
    }

    public function setSeasonReference(?string $seasonReference): static
    {
        $this->seasonReference = $seasonReference;
        return $this;
    }

    public function getTeamReference(): ?string
    {
        return $this->teamReference;
    }

    public function setTeamReference(?string $teamReference): static
    {
        $this->teamReference = $teamReference;
        return $this;
    }

    public function getAthlete(): ?EspnAthlete
    {
        return $this->athlete;
    }

    public function setAthlete(?EspnAthlete $athlete): static
    {
        $this->athlete = $athlete;
        return $this;
    }

    public function getTeam(): ?EspnTeam
    {
        return $this->team;
    }

    public function setTeam(?EspnTeam $team): static
    {
        $this->team = $team;
        return $this;
    }

    public function getSeason(): ?EspnSeason
    {
        return $this->season;
    }

    public function setSeason(?EspnSeason $season): static
    {
        $this->season = $season;
        return $this;
    }
}
