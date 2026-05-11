<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiClient\Dto\EspnSeason as EspnSeasonDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnSeasonTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;

#[ORM\Entity(repositoryClass: EspnSeasonRepository::class)]
#[ORM\Table(name: 'easb_espn_season')]
class EspnSeason
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column]
    private ?\DateTime $startDate = null;

    #[ORM\Column]
    private ?\DateTime $endDate = null;

    #[ORM\Column(length: 255)]
    private ?string $displayName = null;

    #[ORM\Column]
    private ?string $typeReference = null;

    #[ORM\Column]
    private ?string $typesReference = null;

    #[ORM\Column]
    private ?string $rankingsReference = null;

    #[ORM\Column]
    private ?string $futuresReference = null;

    #[ORM\ManyToOne(targetEntity: EspnSeasonType::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "internal_type_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    private ?EspnSeasonType $type = null;

    /**
     * @var Collection<int, EspnSeasonType>
     */
    #[ORM\OneToMany(mappedBy: 'season', targetEntity: EspnSeasonType::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $types;

    public function __construct()
    {
        $this->types = new ArrayCollection();
    }

    public function buildFindByCriteriaFromDto(EspnSeasonDto $espnSeasonDto): array
    {
        return [
            'year' => $espnSeasonDto->getYear(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;
        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): static
    {
        $this->endDate = $endDate;
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

    public function getTypeReference(): ?string
    {
        return $this->typeReference;
    }

    public function setTypeReference(?string $typeReference): static
    {
        $this->typeReference = $typeReference;
        return $this;
    }

    public function getTypesReference(): ?string
    {
        return $this->typesReference;
    }

    public function setTypesReference(?string $typesReference): static
    {
        $this->typesReference = $typesReference;
        return $this;
    }

    public function getRankingsReference(): ?string
    {
        return $this->rankingsReference;
    }

    public function setRankingsReference(?string $rankingsReference): static
    {
        $this->rankingsReference = $rankingsReference;
        return $this;
    }

    public function getFuturesReference(): ?string
    {
        return $this->futuresReference;
    }

    public function setFuturesReference(?string $futuresReference): static
    {
        $this->futuresReference = $futuresReference;
        return $this;
    }

    public function getType(): ?EspnSeasonType
    {
        return $this->type;
    }

    public function setType(?EspnSeasonType $type): EspnSeason
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Collection<int, EspnSeasonType>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(EspnSeasonType $type): static
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
            $type->setSeason($this);
        }

        return $this;
    }

    public function removeType(EspnSeasonType $type): static
    {
        if ($this->types->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getSeason() === $this) {
                $type->setSeason(null);
            }
        }

        return $this;
    }

    public function addOrReplaceType(EspnSeasonType $newType): static
    {
        foreach ($this->types as $key => $existingType) {
            if ($existingType->getId() !== null && $existingType->getId() === $newType->getId()) {
                if ($existingType !== $newType) {
                    $this->types->set($key, $newType);
                    $newType->setSeason($this);
                }
                return $this;
            }
        }

        return $this->addType($newType);
    }
}
