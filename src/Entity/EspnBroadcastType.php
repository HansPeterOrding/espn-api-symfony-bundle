<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EspnBroadcastType
{
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $shortName = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): EspnBroadcastType
    {
        $this->id = $id;
        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): EspnBroadcastType
    {
        $this->shortName = $shortName;
        return $this;
    }
}
