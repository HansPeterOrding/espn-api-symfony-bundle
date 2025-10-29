<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EspnCompetitionStatus
{
    #[ORM\Column]
    private ?float $clock = null;

    #[ORM\Column(length: 255)]
    private ?string $displayClock = null;

    #[ORM\Column]
    private ?int $period = null;

    #[ORM\Embedded(class: EspnCompetitionStatusType::class, columnPrefix: 'type_')]
    private ?EspnCompetitionStatusType $type = null;

    #[ORM\Column]
    private ?bool $isTBDFlex = null;

    public function __construct()
    {
        $this->type = new EspnCompetitionStatusType();
    }

    public function getClock(): ?float
    {
        return $this->clock;
    }

    public function setClock(float $clock): static
    {
        $this->clock = $clock;

        return $this;
    }

    public function getDisplayClock(): ?string
    {
        return $this->displayClock;
    }

    public function setDisplayClock(string $displayClock): static
    {
        $this->displayClock = $displayClock;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getType(): ?EspnCompetitionStatusType
    {
        return $this->type;
    }

    public function setType(?EspnCompetitionStatusType $type): EspnCompetitionStatus
    {
        $this->type = $type;
        return $this;
    }

    public function isTBDFlex(): ?bool
    {
        return $this->isTBDFlex;
    }

    public function setIsTBDFlex(bool $isTBDFlex): static
    {
        $this->isTBDFlex = $isTBDFlex;

        return $this;
    }
}
