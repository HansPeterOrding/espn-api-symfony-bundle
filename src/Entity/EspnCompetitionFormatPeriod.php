<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EspnCompetitionFormatPeriod
{
    #[ORM\Column(nullable: true)]
    private ?int $periods = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?string $clock = null;

    public function getPeriods(): ?int
    {
        return $this->periods;
    }

    public function setPeriods(?int $periods): static
    {
        $this->periods = $periods;
        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): static
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;
        return $this;
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
}
