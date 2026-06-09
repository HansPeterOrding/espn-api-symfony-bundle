<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\AthleteStatusTypeEnum;

#[ORM\Embeddable]
class EspnAthleteStatus
{
    #[ORM\Column(nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true, enumType: AthleteStatusTypeEnum::class)]
    private ?AthleteStatusTypeEnum $type = null;

    #[ORM\Column(nullable: true)]
    private ?string $abbreviation = null;

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

    public function getType(): ?AthleteStatusTypeEnum
    {
        return $this->type;
    }

    public function setType(?AthleteStatusTypeEnum $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): static
    {
        $this->abbreviation = $abbreviation;
        return $this;
    }
}
