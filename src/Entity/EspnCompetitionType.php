<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnCompetitionTypeTypeEnum;

#[ORM\Embeddable]
class EspnCompetitionType
{
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;

    #[ORM\Column(length: 255)]
    private ?string $abbreviation = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(enumType: EspnCompetitionTypeTypeEnum::class)]
    private ?EspnCompetitionTypeTypeEnum $type = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): EspnCompetitionType
    {
        $this->id = $id;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): EspnCompetitionType
    {
        $this->text = $text;
        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): EspnCompetitionType
    {
        $this->abbreviation = $abbreviation;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): EspnCompetitionType
    {
        $this->slug = $slug;
        return $this;
    }

    public function getType(): ?EspnCompetitionTypeTypeEnum
    {
        return $this->type;
    }

    public function setType(?EspnCompetitionTypeTypeEnum $type): EspnCompetitionType
    {
        $this->type = $type;
        return $this;
    }
}
