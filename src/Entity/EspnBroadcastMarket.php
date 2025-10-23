<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnBroadcastMarketType;

#[ORM\Embeddable]
class EspnBroadcastMarket
{
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(enumType: EspnBroadcastMarketType::class)]
    private ?EspnBroadcastMarketType $type = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): EspnBroadcastMarket
    {
        $this->id = $id;
        return $this;
    }

    public function getType(): ?EspnBroadcastMarketType
    {
        return $this->type;
    }

    public function setType(?EspnBroadcastMarketType $type): EspnBroadcastMarket
    {
        $this->type = $type;
        return $this;
    }
}
