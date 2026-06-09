<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\CompetitionStatusStateEnum;

#[ORM\Embeddable]
class EspnCompetitionStatusType
{
    #[ORM\Column(nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true, enumType: CompetitionStatusStateEnum::class)]
    private ?CompetitionStatusStateEnum $state = null;

    #[ORM\Column(nullable: true)]
    private ?bool $completed = null;

    #[ORM\Column(nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?string $detail = null;

    #[ORM\Column(nullable: true)]
    private ?string $shortDetail = null;

    public function getEspnId(): ?string
    {
        return $this->espnId;
    }

    public function setEspnId(?string $espnId): static
    {
        $this->espnId = $espnId;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getState(): ?CompetitionStatusStateEnum
    {
        return $this->state;
    }

    public function setState(?CompetitionStatusStateEnum $state): static
    {
        $this->state = $state;
        return $this;
    }

    public function getCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(?bool $completed): static
    {
        $this->completed = $completed;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): static
    {
        $this->detail = $detail;
        return $this;
    }

    public function getShortDetail(): ?string
    {
        return $this->shortDetail;
    }

    public function setShortDetail(?string $shortDetail): static
    {
        $this->shortDetail = $shortDetail;
        return $this;
    }
}
