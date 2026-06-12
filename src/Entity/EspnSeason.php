<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnSeason as EspnSeasonDto;

#[ORM\Entity(repositoryClass: EspnSeasonRepository::class)]
#[ORM\Table(name: 'easb_espn_season')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(name: 'idx_espn_season_year', columns: ['espn_year'])]
class EspnSeason
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(unique: true, nullable: true)]
    private ?int $espnYear = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?string $typeReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $typesReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $rankingsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $coachesReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $athletesReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $awardsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $futuresReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $leadersReference = null;

    /**
     * @var Collection<int, EspnSeasonType>
     */
    #[ORM\OneToMany(
        targetEntity: EspnSeasonType::class,
        mappedBy: 'season',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $seasonTypes;

    public function __construct()
    {
        $this->seasonTypes = new ArrayCollection();
    }

    public static function buildFindByCriteriaFromDto(EspnSeasonDto $dto): array
    {
        return [
            'espnYear' => $dto->getYear(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEspnYear(): ?int
    {
        return $this->espnYear;
    }

    public function setEspnYear(?int $espnYear): static
    {
        $this->espnYear = $espnYear;
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

    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;
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

    public function getCoachesReference(): ?string
    {
        return $this->coachesReference;
    }

    public function setCoachesReference(?string $coachesReference): static
    {
        $this->coachesReference = $coachesReference;
        return $this;
    }

    public function getAthletesReference(): ?string
    {
        return $this->athletesReference;
    }

    public function setAthletesReference(?string $athletesReference): static
    {
        $this->athletesReference = $athletesReference;
        return $this;
    }

    public function getAwardsReference(): ?string
    {
        return $this->awardsReference;
    }

    public function setAwardsReference(?string $awardsReference): static
    {
        $this->awardsReference = $awardsReference;
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

    public function getLeadersReference(): ?string
    {
        return $this->leadersReference;
    }

    public function setLeadersReference(?string $leadersReference): static
    {
        $this->leadersReference = $leadersReference;
        return $this;
    }

    public function getSeasonTypes(): Collection
    {
        return $this->seasonTypes;
    }

    public function addSeasonType(EspnSeasonType $seasonType): static
    {
        if (!$this->seasonTypes->contains($seasonType)) {
            $this->seasonTypes->add($seasonType);
            $seasonType->setSeason($this);
        }
        return $this;
    }

    public function removeSeasonType(EspnSeasonType $seasonType): static
    {
        if ($this->seasonTypes->removeElement($seasonType)) {
            if ($seasonType->getSeason() === $this) {
                $seasonType->setSeason(null);
            }
        }
        return $this;
    }

    public function removeAllSeasonTypes(): static
    {
        foreach ($this->seasonTypes as $seasonType) {
            $this->removeSeasonType($seasonType);
        }
        return $this;
    }

    public function addOrReplaceSeasonType(EspnSeasonType $newSeasonType): static
    {
        foreach ($this->seasonTypes as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newSeasonType->getId()) {
                if ($existing !== $newSeasonType) {
                    $this->seasonTypes->set($key, $newSeasonType);
                    $newSeasonType->setSeason($this);
                }
                return $this;
            }
        }
        return $this->addSeasonType($newSeasonType);
    }

}
