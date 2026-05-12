<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use HansPeterOrding\EspnApiClient\Dto\EspnFranchise as EspnFranchiseDto;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnFranchiseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EspnFranchiseRepository::class)]
#[ORM\Table(name: 'easb_espn_franchise')]
class EspnFranchise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $espnId = null;

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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $venueReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $teamReference = null;

    /**
     * @var Collection<int, EspnSeasonTeam>
     */
    #[ORM\OneToMany(mappedBy: 'franchise', targetEntity: EspnSeasonTeam::class)]
    private Collection $teams;

    #[ORM\ManyToOne(inversedBy: 'franchises')]
    private ?EspnVenue $venue = null;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function buildFindByCriteriaFromDto(EspnFranchiseDto $espnFranchiseDto): array
    {
        return [
            'espnId' => $espnFranchiseDto->getId(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEspnId(): ?string
    {
        return $this->espnId;
    }

    public function setEspnId(?string $espnId): static
    {
        $this->espnId = $espnId;

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

    public function getVenueReference(): ?string
    {
        return $this->venueReference;
    }

    public function setVenueReference(?string $venueReference): static
    {
        $this->venueReference = $venueReference;

        return $this;
    }

    public function getTeamReference(): ?string
    {
        return $this->teamReference;
    }

    public function setTeamReference(?string $teamReference): static
    {
        $this->teamReference = $teamReference;

        return $this;
    }

    /**
     * @return Collection<int, EspnSeasonTeam>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(EspnSeasonTeam $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->setFranchise($this);
        }

        return $this;
    }

    public function removeTeam(EspnSeasonTeam $team): static
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getFranchise() === $this) {
                $team->setFranchise(null);
            }
        }

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
}
