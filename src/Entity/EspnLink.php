<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnLinkRepository;

#[ORM\Entity(repositoryClass: EspnLinkRepository::class)]
#[ORM\Table(name: 'easb_espn_link')]
class EspnLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $language = null;

    #[ORM\Column]
    private array $rel = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $href = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortText = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isExternal = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPremium = null;

    #[ORM\ManyToOne(inversedBy: 'links')]
    private ?EspnSeasonTeam $team = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): static
    {
        $this->language = $language;

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

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): static
    {
        $this->href = $href;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getShortText(): ?string
    {
        return $this->shortText;
    }

    public function setShortText(?string $shortText): static
    {
        $this->shortText = $shortText;

        return $this;
    }

    public function isExternal(): ?bool
    {
        return $this->isExternal;
    }

    public function setIsExternal(?bool $isExternal): static
    {
        $this->isExternal = $isExternal;

        return $this;
    }

    public function isPremium(): ?bool
    {
        return $this->isPremium;
    }

    public function setIsPremium(?bool $isPremium): static
    {
        $this->isPremium = $isPremium;

        return $this;
    }

    public function getTeam(): ?EspnSeasonTeam
    {
        return $this->team;
    }

    public function setTeam(?EspnSeasonTeam $team): static
    {
        $this->team = $team;

        return $this;
    }
}
