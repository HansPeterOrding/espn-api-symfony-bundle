<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EspnBroadcastMedia
{
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $shortName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $darkLogo = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): EspnBroadcastMedia
    {
        $this->id = $id;
        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): EspnBroadcastMedia
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): EspnBroadcastMedia
    {
        $this->logo = $logo;
        return $this;
    }

    public function getDarkLogo(): ?string
    {
        return $this->darkLogo;
    }

    public function setDarkLogo(?string $darkLogo): EspnBroadcastMedia
    {
        $this->darkLogo = $darkLogo;
        return $this;
    }
}
