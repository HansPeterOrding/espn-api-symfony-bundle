<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EspnBroadcastType
{
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $shortName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
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
}
