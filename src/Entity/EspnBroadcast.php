<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiClient\Dto\EspnBroadcast as EspnBroadcastDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnBroadcastMarketEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnBroadcastMediaEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnBroadcastTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnBroadcastRepository;

#[ORM\Entity(repositoryClass: EspnBroadcastRepository::class)]
class EspnBroadcast
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: EspnBroadcastTypeEnum::class)]
    private ?EspnBroadcastTypeEnum $type = null;

    #[ORM\Column(enumType: EspnBroadcastMarketEnum::class)]
    private ?EspnBroadcastMarketEnum $market = null;

    #[ORM\Column(enumType: EspnBroadcastMediaEnum::class)]
    private ?EspnBroadcastMediaEnum $media = null;

    #[ORM\Column(length: 255)]
    private ?string $lang = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column]
    private ?bool $partnered = null;

    #[ORM\ManyToOne(inversedBy: 'broadcasts')]
    private ?EspnCompetition $competition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): EspnBroadcast
    {
        $this->id = $id;
        return $this;
    }

    public function getType(): ?EspnBroadcastTypeEnum
    {
        return $this->type;
    }

    public function setType(?EspnBroadcastTypeEnum $type): EspnBroadcast
    {
        $this->type = $type;
        return $this;
    }

    public function getMarket(): ?EspnBroadcastMarketEnum
    {
        return $this->market;
    }

    public function setMarket(?EspnBroadcastMarketEnum $market): EspnBroadcast
    {
        $this->market = $market;
        return $this;
    }

    public function getMedia(): ?EspnBroadcastMediaEnum
    {
        return $this->media;
    }

    public function setMedia(?EspnBroadcastMediaEnum $media): EspnBroadcast
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

    public function getCompetition(): ?EspnCompetition
    {
        return $this->competition;
    }

    public function setCompetition(?EspnCompetition $competition): static
    {
        $this->competition = $competition;

        return $this;
    }
}
