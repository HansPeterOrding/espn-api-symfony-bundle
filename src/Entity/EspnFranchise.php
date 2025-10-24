<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnFranchiseRepository;

#[ORM\Entity(repositoryClass: EspnFranchiseRepository::class)]
class EspnFranchise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $franchiseId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nickname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortDisplayName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    #[ORM\ManyToOne(inversedBy: 'franchises')]
    private ?EspnVenue $venue = null;

    #[ORM\OneToOne(mappedBy: 'franchise', cascade: ['persist', 'remove'])]
    private ?EspnTeam $team = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFranchiseId(): ?string
    {
        return $this->franchiseId;
    }

    public function setFranchiseId(?string $franchiseId): static
    {
        $this->franchiseId = $franchiseId;

        return $this;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid): static
    {
        $this->uid = $uid;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): static
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getShortDisplayName(): ?string
    {
        return $this->shortDisplayName;
    }

    public function setShortDisplayName(?string $shortDisplayName): static
    {
        $this->shortDisplayName = $shortDisplayName;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getVenue(): ?EspnVenue
    {
        return $this->venue;
    }

    public function setVenue(?EspnVenue $venue): static
    {
        $this->venue = $venue;

        return $this;
    }

    public function getTeam(): ?EspnTeam
    {
        return $this->team;
    }

    public function setTeam(?EspnTeam $team): static
    {
        // unset the owning side of the relation if necessary
        if ($team === null && $this->team !== null) {
            $this->team->setFranchise(null);
        }

        // set the owning side of the relation if necessary
        if ($team !== null && $team->getFranchise() !== $this) {
            $team->setFranchise($this);
        }

        $this->team = $team;

        return $this;
    }
}
