<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeGroup as EspnSeasonTypeGroupDto;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeGroupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EspnSeasonTypeGroupRepository::class)]
#[ORM\Table(name: 'easb_espn_season_type_group')]
class EspnSeasonTypeGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $seasonReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $childrenReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $parentReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $standingsReference = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isConference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $teamsReference = null;

    /**
     * @var Collection<int, EspnSeasonType>
     */
    #[ORM\ManyToMany(targetEntity: EspnSeasonType::class, mappedBy: 'groups')]
    private Collection $types;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class, cascade: ['persist'])]
    private Collection $children;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function buildFindByCriteriaFromDto(EspnSeasonTypeGroupDto $espnSeasonTypeGroupDto): array
    {
        return [
            'uid' => $espnSeasonTypeGroupDto->getUid(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEspnId(): ?string
    {
        return $this->espnId;
    }

    public function setEspnId(?string $espnId): static
    {
        $this->espnId = $espnId;

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

    public function getSeasonReference(): ?string
    {
        return $this->seasonReference;
    }

    public function setSeasonReference(?string $seasonReference): static
    {
        $this->seasonReference = $seasonReference;

        return $this;
    }

    public function getChildrenReference(): ?string
    {
        return $this->childrenReference;
    }

    public function setChildrenReference(?string $childrenReference): static
    {
        $this->childrenReference = $childrenReference;

        return $this;
    }

    public function getParentReference(): ?string
    {
        return $this->parentReference;
    }

    public function setParentReference(?string $parentReference): static
    {
        $this->parentReference = $parentReference;

        return $this;
    }

    public function getStandingsReference(): ?string
    {
        return $this->standingsReference;
    }

    public function setStandingsReference(?string $standingsReference): static
    {
        $this->standingsReference = $standingsReference;

        return $this;
    }

    public function isConference(): ?bool
    {
        return $this->isConference;
    }

    public function setIsConference(?bool $isConference): static
    {
        $this->isConference = $isConference;

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

    public function getTeamsReference(): ?string
    {
        return $this->teamsReference;
    }

    public function setTeamsReference(?string $teamsReference): static
    {
        $this->teamsReference = $teamsReference;

        return $this;
    }

    /**
     * @return Collection<int, EspnSeasonType>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }
        return $this;
    }

    public function addOrReplaceChild(EspnSeasonTypeGroup $newChild): static
    {
        foreach ($this->children as $key => $existingChild) {
            if ($existingChild->getId() !== null && $existingChild->getId() === $newChild->getId()) {
                if ($existingChild !== $newChild) {
                    $this->children->set($key, $newChild);
                    $newChild->setParent($this);
                }
                return $this;
            }
        }

        return $this->addChild($newChild);
    }
}
