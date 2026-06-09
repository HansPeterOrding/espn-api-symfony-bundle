<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\SeasonTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonType as EspnSeasonTypeDto;

#[ORM\Entity(repositoryClass: EspnSeasonTypeRepository::class)]
#[ORM\Table(name: 'easb_espn_season_type')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_espn_season_type', columns: ['espn_id', 'season_id'])]
class EspnSeasonType
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true, enumType: SeasonTypeEnum::class)]
    private ?SeasonTypeEnum $type = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column(nullable: true)]
    private ?int $year = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasGroups = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasStandings = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasLegs = null;

    #[ORM\Column(nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isCurrent = null;

    #[ORM\Column(nullable: true)]
    private ?string $groupsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $weeksReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $correctionsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $leadersReference = null;

    #[ORM\ManyToOne(targetEntity: EspnSeason::class, inversedBy: 'seasonTypes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnSeason $season = null;

    /**
     * @var Collection<int, EspnWeek>
     */
    #[ORM\OneToMany(
        mappedBy: 'seasonType',
        targetEntity: EspnWeek::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $weeks;

    public function __construct()
    {
        $this->weeks = new ArrayCollection();
    }

    public static function buildFindByCriteriaFromDto(EspnSeasonTypeDto $dto, EspnSeason $season): array
    {
        return [
            'espnId' => $dto->getId(),
            'season' => $season,
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

    public function getType(): ?SeasonTypeEnum
    {
        return $this->type;
    }

    public function setType(?SeasonTypeEnum $type): static
    {
        $this->type = $type;
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;
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

    public function getHasGroups(): ?bool
    {
        return $this->hasGroups;
    }

    public function setHasGroups(?bool $hasGroups): static
    {
        $this->hasGroups = $hasGroups;
        return $this;
    }

    public function getHasStandings(): ?bool
    {
        return $this->hasStandings;
    }

    public function setHasStandings(?bool $hasStandings): static
    {
        $this->hasStandings = $hasStandings;
        return $this;
    }

    public function getHasLegs(): ?bool
    {
        return $this->hasLegs;
    }

    public function setHasLegs(?bool $hasLegs): static
    {
        $this->hasLegs = $hasLegs;
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

    public function getIsCurrent(): ?bool
    {
        return $this->isCurrent;
    }

    public function setIsCurrent(?bool $isCurrent): static
    {
        $this->isCurrent = $isCurrent;
        return $this;
    }

    public function getGroupsReference(): ?string
    {
        return $this->groupsReference;
    }

    public function setGroupsReference(?string $v): static
    {
        $this->groupsReference = $v;
        return $this;
    }

    public function getWeeksReference(): ?string
    {
        return $this->weeksReference;
    }

    public function setWeeksReference(?string $v): static
    {
        $this->weeksReference = $v;
        return $this;
    }

    public function getCorrectionsReference(): ?string
    {
        return $this->correctionsReference;
    }

    public function setCorrectionsReference(?string $v): static
    {
        $this->correctionsReference = $v;
        return $this;
    }

    public function getLeadersReference(): ?string
    {
        return $this->leadersReference;
    }

    public function setLeadersReference(?string $v): static
    {
        $this->leadersReference = $v;
        return $this;
    }

    public function getSeason(): ?EspnSeason
    {
        return $this->season;
    }

    public function setSeason(?EspnSeason $season): static
    {
        $this->season = $season;
        return $this;
    }

    public function getWeeks(): Collection
    {
        return $this->weeks;
    }

    public function addWeek(EspnWeek $week): static
    {
        if (!$this->weeks->contains($week)) {
            $this->weeks->add($week);
            $week->setSeasonType($this);
        }
        return $this;
    }

    public function removeWeek(EspnWeek $week): static
    {
        if ($this->weeks->removeElement($week)) {
            if ($week->getSeasonType() === $this) {
                $week->setSeasonType(null);
            }
        }
        return $this;
    }

    public function removeAllWeeks(): static
    {
        foreach ($this->weeks as $week) {
            $this->removeWeek($week);
        }
        return $this;
    }

    public function addOrReplaceWeek(EspnWeek $newWeek): static
    {
        foreach ($this->weeks as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newWeek->getId()) {
                if ($existing !== $newWeek) {
                    $this->weeks->set($key, $newWeek);
                    $newWeek->setSeasonType($this);
                }
                return $this;
            }
        }
        return $this->addWeek($newWeek);
    }
}
