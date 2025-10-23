<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EspnBroadcastMedia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $shortName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $darkLogo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): static
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getDarkLogo(): ?string
    {
        return $this->darkLogo;
    }

    public function setDarkLogo(?string $darkLogo): static
    {
        $this->darkLogo = $darkLogo;

        return $this;
    }
}
