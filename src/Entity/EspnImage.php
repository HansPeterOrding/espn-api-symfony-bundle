<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnImageRepository;

#[ORM\Entity(repositoryClass: EspnImageRepository::class)]
class EspnImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $href = null;

    #[ORM\Column]
    private ?int $width = null;

    #[ORM\Column]
    private ?int $height = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alt = null;

    #[ORM\Column(type: Types::JSON)]
    private array $rel = [];

    #[ORM\Column(nullable: true)]
    private ?\DateTime $lastUpdated = null;

    #[ORM\ManyToOne(inversedBy: 'logos')]
    private ?EspnTeam $espnTeam = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(string $href): static
    {
        $this->href = $href;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): static
    {
        $this->alt = $alt;

        return $this;
    }

    public function getRel(): array
    {
        return $this->rel;
    }

    public function setRel(array $rel): static
    {
        $this->rel = $rel;

        return $this;
    }

    public function getLastUpdated(): ?\DateTime
    {
        return $this->lastUpdated;
    }

    public function setLastUpdated(?\DateTime $lastUpdated): static
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    public function getEspnTeam(): ?EspnTeam
    {
        return $this->espnTeam;
    }

    public function setEspnTeam(?EspnTeam $espnTeam): static
    {
        $this->espnTeam = $espnTeam;

        return $this;
    }
}
