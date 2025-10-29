<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnCompetitionStatusTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnCompetitionStatusTypeStateEnum;

#[ORM\Embeddable]
class EspnCompetitionStatusType
{
    #[ORM\Column(enumType: EspnCompetitionStatusTypeEnum::class)]
    private ?EspnCompetitionStatusTypeEnum $type = null;

    #[ORM\Column(length: 255)]
    private ?string $detail = null;

    #[ORM\Column(length: 255)]
    private ?string $shortDetail = null;

    public function getType(): ?EspnCompetitionStatusTypeEnum
    {
        return $this->type;
    }

    public function setType(?EspnCompetitionStatusTypeEnum $type): EspnCompetitionStatusType
    {
        $this->type = $type;
        return $this;
    }

    public function getState(): ?string
    {
        return $this->type?->getState();
    }

    public function isCompleted(): ?bool
    {
        return $this->type?->isCompleted();
    }

    public function getDescription(): ?string
    {
        return $this->type?->getDescription();
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
