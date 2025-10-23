<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Entity\Enum\EspnBroadcastMarketType;

#[ORM\Embeddable]
class EspnBroadcastMarket
{
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: EspnBroadcastMarketType::class)]
    private ?EspnBroadcastMarketType $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): ?EspnBroadcastMarketType
    {
        return $this->type;
    }

    public function setType(EspnBroadcastMarketType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
