<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnBroadcastRepository;

#[ORM\Entity(repositoryClass: EspnBroadcastRepository::class)]
class EspnBroadcast
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Embedded(class: EspnBroadcastType::class, columnPrefix: 'type_')]
    private ?EspnBroadcastType $type = null;

    #[ORM\Embedded(class: EspnBroadcastMarket::class, columnPrefix: 'market_')]
    private ?EspnBroadcastMarket $market = null;

    #[ORM\Embedded(class: EspnBroadcastMedia::class, columnPrefix: 'media_')]
    private ?EspnBroadcastMedia $media = null;

    #[ORM\Column(length: 255)]
    private ?string $lang = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column]
    private ?bool $partnered = null;

    public function __construct()
    {
        $this->type = new EspnBroadcastType();
        $this->market = new EspnBroadcastMarket();
        $this->media = new EspnBroadcastMedia();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): EspnBroadcast
    {
        $this->id = $id;
        return $this;
    }

    public function getType(): ?EspnBroadcastType
    {
        return $this->type;
    }

    public function setType(?EspnBroadcastType $type): EspnBroadcast
    {
        $this->type = $type;
        return $this;
    }

    public function getMarket(): ?EspnBroadcastMarket
    {
        return $this->market;
    }

    public function setMarket(?EspnBroadcastMarket $market): EspnBroadcast
    {
        $this->market = $market;
        return $this;
    }

    public function getMedia(): ?EspnBroadcastMedia
    {
        return $this->media;
    }

    public function setMedia(?EspnBroadcastMedia $media): EspnBroadcast
    {
        $this->media = $media;
        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): EspnBroadcast
    {
        $this->lang = $lang;
        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): EspnBroadcast
    {
        $this->region = $region;
        return $this;
    }

    public function getPartnered(): ?bool
    {
        return $this->partnered;
    }

    public function setPartnered(?bool $partnered): EspnBroadcast
    {
        $this->partnered = $partnered;
        return $this;
    }
}
