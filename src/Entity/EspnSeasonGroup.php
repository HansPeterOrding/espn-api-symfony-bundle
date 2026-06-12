<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonGroupRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonGroup as EspnSeasonGroupDto;

#[ORM\Entity(repositoryClass: EspnSeasonGroupRepository::class)]
#[ORM\Table(name: 'easb_espn_season_group')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_espn_season_group', columns: ['espn_id'])]
class EspnSeasonGroup
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $uid = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column(nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isConference = null;

    #[ORM\Column(nullable: true)]
    private ?string $standingsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $teamsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $childrenReference = null;

    #[ORM\ManyToOne(targetEntity: EspnSeasonGroup::class, inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnSeasonGroup $parent = null;

    /**
     * @var Collection<int, EspnSeasonGroup>
     */
    #[ORM\OneToMany(
        targetEntity: EspnSeasonGroup::class,
        mappedBy: 'parent',
        cascade: ['persist'],
        orphanRemoval: false
    )]
    private Collection $children;

    /**
     * @var Collection<int, EspnTeam>
     */
    #[ORM\ManyToMany(
        targetEntity: EspnTeam::class,
        mappedBy: 'seasonGroups'
    )]
    private Collection $teams;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public static function buildFindByCriteriaFromDto(EspnSeasonGroupDto $dto): array
    {
        return [
            'espnId' => $dto->getId(),
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getIsConference(): ?bool
    {
        return $this->isConference;
    }

    public function setIsConference(?bool $isConference): static
    {
        $this->isConference = $isConference;
        return $this;
    }

    public function getStandingsReference(): ?string
    {
        return $this->standingsReference;
    }

    public function setStandingsReference(?string $v): static
    {
        $this->standingsReference = $v;
        return $this;
    }

    public function getTeamsReference(): ?string
    {
        return $this->teamsReference;
    }

    public function setTeamsReference(?string $v): static
    {
        $this->teamsReference = $v;
        return $this;
    }

    public function getChildrenReference(): ?string
    {
        return $this->childrenReference;
    }

    public function setChildrenReference(?string $v): static
    {
        $this->childrenReference = $v;
        return $this;
    }

    public function getParent(): ?EspnSeasonGroup
    {
        return $this->parent;
    }

    public function setParent(?EspnSeasonGroup $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(EspnSeasonGroup $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
        return $this;
    }

    public function removeChild(EspnSeasonGroup $child): static
    {
        if ($this->children->removeElement($child)) {
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }
        return $this;
    }

    public function removeAllChildren(): static
    {
        foreach ($this->children as $child) {
            $this->removeChild($child);
        }
        return $this;
    }

    public function addOrReplaceChild(EspnSeasonGroup $newChild): static
    {
        foreach ($this->children as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newChild->getId()) {
                if ($existing !== $newChild) {
                    $this->children->set($key, $newChild);
                    $newChild->setParent($this);
                }
                return $this;
            }
        }
        return $this->addChild($newChild);
    }

    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(EspnTeam $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->addSeasonGroup($this);
        }
        return $this;
    }

    public function removeTeam(EspnTeam $team): static
    {
        if ($this->teams->removeElement($team)) {
            $team->removeSeasonGroup($this);
        }
        return $this;
    }

    public function removeAllTeams(): static
    {
        foreach ($this->teams as $team) {
            $this->removeTeam($team);
        }
        return $this;
    }

    public function addOrReplaceTeam(EspnTeam $team): static
    {
        return $this->addTeam($team);
    }

}
