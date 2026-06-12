<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnPositionRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnPosition as EspnPositionDto;

#[ORM\Entity(repositoryClass: EspnPositionRepository::class)]
#[ORM\Table(name: 'easb_espn_position')]
#[ORM\HasLifecycleCallbacks]
class EspnPosition
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $leaf = null;

    #[ORM\ManyToOne(targetEntity: EspnPosition::class, inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnPosition $parent = null;

    /**
     * @var Collection<int, EspnPosition>
     */
    #[ORM\OneToMany(
        targetEntity: EspnPosition::class,
        mappedBy: 'parent',
        cascade: ['persist'],
        orphanRemoval: false
    )]
    private Collection $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public static function buildFindByCriteriaFromDto(EspnPositionDto $dto): array
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
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

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): static
    {
        $this->abbreviation = $abbreviation;
        return $this;
    }

    public function getLeaf(): ?bool
    {
        return $this->leaf;
    }

    public function setLeaf(?bool $leaf): static
    {
        $this->leaf = $leaf;
        return $this;
    }

    public function getParent(): ?EspnPosition
    {
        return $this->parent;
    }

    public function setParent(?EspnPosition $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(EspnPosition $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
        return $this;
    }

    public function removeChild(EspnPosition $child): static
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

    public function addOrReplaceChild(EspnPosition $newChild): static
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
}
