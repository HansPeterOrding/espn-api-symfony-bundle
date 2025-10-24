<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnCompetitionStatusTypeStateEnum;

#[ORM\Embeddable]
class EspnCompetitionStatusType
{
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(enumType: EspnCompetitionStatusTypeStateEnum::class)]
    private ?EspnCompetitionStatusTypeStateEnum $state = null;

    #[ORM\Column]
    private ?bool $completed = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $detail = null;

    #[ORM\Column(length: 255)]
    private ?string $shortDetail = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): EspnCompetitionStatusType
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getState(): ?EspnCompetitionStatusTypeStateEnum
    {
        return $this->state;
    }

    public function setState(EspnCompetitionStatusTypeStateEnum $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function isCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): static
    {
        $this->completed = $completed;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    public function getShortDetail(): ?string
    {
        return $this->shortDetail;
    }

    public function setShortDetail(string $shortDetail): static
    {
        $this->shortDetail = $shortDetail;

        return $this;
    }
}
