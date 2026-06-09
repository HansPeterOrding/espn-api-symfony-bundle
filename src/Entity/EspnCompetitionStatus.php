<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitionStatusRepository;

#[ORM\Entity(repositoryClass: EspnCompetitionStatusRepository::class)]
#[ORM\Table(name: 'easb_espn_competition_status')]
#[ORM\HasLifecycleCallbacks]
class EspnCompetitionStatus
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $clock = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayClock = null;

    #[ORM\Column(nullable: true)]
    private ?int $period = null;

    #[ORM\Embedded(class: EspnCompetitionStatusType::class, columnPrefix: 'type_')]
    private EspnCompetitionStatusType $type;

    #[ORM\OneToOne(targetEntity: EspnCompetition::class, inversedBy: 'status')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnCompetition $competition = null;

    public function __construct()
    {
        $this->type = new EspnCompetitionStatusType();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClock(): ?string
    {
        return $this->clock;
    }

    public function setClock(?string $clock): static
    {
        $this->clock = $clock;
        return $this;
    }

    public function getDisplayClock(): ?string
    {
        return $this->displayClock;
    }

    public function setDisplayClock(?string $displayClock): static
    {
        $this->displayClock = $displayClock;
        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(?int $period): static
    {
        $this->period = $period;
        return $this;
    }

    public function getType(): EspnCompetitionStatusType
    {
        return $this->type;
    }

    public function setType(EspnCompetitionStatusType $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getCompetition(): ?EspnCompetition
    {
        return $this->competition;
    }

    public function setCompetition(?EspnCompetition $competition): static
    {
        $this->competition = $competition;
        return $this;
    }
}
