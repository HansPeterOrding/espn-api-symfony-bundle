<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonType as EspnSeasonTypeDto;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeRepository;

#[ORM\Entity(repositoryClass: EspnSeasonTypeRepository::class)]
#[ORM\Table(name: 'easb_espn_season_type')]
class EspnSeasonType
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $espnId = null;

    #[ORM\Column]
    private ?int $type = null;

    #[ORM\Column]
    private ?string $name = null;

    #[ORM\Column]
    private ?string $abbreviation = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column]
    private ?DateTime $startDate = null;

    #[ORM\Column]
    private ?DateTime $endDate = null;

    #[ORM\Column]
    private ?bool $hasGroups = null;

    #[ORM\Column]
    private ?bool $hasStandings = null;

    #[ORM\Column]
    private ?bool $hasLegs = null;

    #[ORM\Column(nullable: true)]
    private ?string $groupsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $weeksReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $correctionsReference = null;

    #[ORM\Column]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'types')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnSeason $season = null;

    /**
     * @var Collection<int, EspnSeasonTypeGroup>
     */
    #[ORM\ManyToMany(targetEntity: EspnSeasonTypeGroup::class, inversedBy: 'types', cascade: ['persist', 'remove'])]
    #[ORM\JoinTable(name: 'easb_espn_season_type_to_groups')]
    private Collection $groups;

    /**
     * @var Collection<int, EspnSeasonTypeWeek>
     */
    #[ORM\OneToMany(mappedBy: 'type', targetEntity: EspnSeasonTypeWeek::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $weeks;

    /**
     * @var Collection<int, EspnSeasonTypeTeamRecord>
     */
    #[ORM\OneToMany(mappedBy: 'seasonType', targetEntity: EspnSeasonTypeTeamRecord::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $records;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->weeks = new ArrayCollection();
        $this->records = new ArrayCollection();
    }

    public function buildFindByCriteriaFromDto(EspnSeasonTypeDto $espnSeasonTypeDto): array
    {
        return [
            'year' => $espnSeasonTypeDto->getYear(),
            'type' => $espnSeasonTypeDto->getType(),
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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): static
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

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): static
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): static
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

    public function getGroupsReference(): ?string
    {
        return $this->groupsReference;
    }

    public function setGroupsReference(?string $groupsReference): static
    {
        $this->groupsReference = $groupsReference;
        return $this;
    }

    public function getWeeksReference(): ?string
    {
        return $this->weeksReference;
    }

    public function setWeeksReference(?string $weeksReference): static
    {
        $this->weeksReference = $weeksReference;
        return $this;
    }

    public function getCorrectionsReference(): ?string
    {
        return $this->correctionsReference;
    }

    public function setCorrectionsReference(?string $correctionsReference): static
    {
        $this->correctionsReference = $correctionsReference;
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

    public function getSeason(): ?EspnSeason
    {
        return $this->season;
    }

    public function setSeason(?EspnSeason $season): static
    {
        $this->season = $season;

        return $this;
    }

    /**
     * @return Collection<int, EspnSeasonTypeGroup>
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(EspnSeasonTypeGroup $group): static
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
        return $this;
    }

    public function removeGroup(EspnSeasonTypeGroup $group): static
    {
        $this->groups->removeElement($group);
        return $this;
    }

    public function addOrReplaceGroup(EspnSeasonTypeGroup $newGroup): static
    {
        foreach ($this->groups as $key => $existingGroup) {
            if ($existingGroup->getId() !== null && $existingGroup->getId() === $newGroup->getId()) {
                if ($existingGroup !== $newGroup) {
                    $this->groups->set($key, $newGroup);
                    $newGroup->setSeasonType($this);
                }
                return $this;
            }
        }

        return $this->addGroup($newGroup);
    }

    /**
     * @return Collection<int, EspnSeasonTypeWeek>
     */
    public function getWeeks(): Collection
    {
        return $this->weeks;
    }

    public function addWeek(EspnSeasonTypeWeek $week): static
    {
        if (!$this->weeks->contains($week)) {
            $this->weeks->add($week);
            $week->setType($this);
        }

        return $this;
    }

    public function removeWeek(EspnSeasonTypeWeek $week): static
    {
        if ($this->weeks->removeElement($week)) {
            // set the owning side to null (unless already changed)
            if ($week->getType() === $this) {
                $week->setType(null);
            }
        }

        return $this;
    }

    public function addOrReplaceWeek(EspnSeasonTypeWeek $newWeek): static
    {
        foreach ($this->weeks as $key => $existingWeek) {
            if ($existingWeek->getId() !== null && $existingWeek->getId() === $newWeek->getId()) {
                if ($existingWeek !== $newWeek) {
                    $this->weeks->set($key, $newWeek);
                    $newWeek->setSeasonType($this);
                }
                return $this;
            }
        }

        return $this->addWeek($newWeek);
    }

    /**
     * @return Collection<int, EspnSeasonTypeTeamRecord>
     */
    public function getRecords(): Collection
    {
        return $this->records;
    }

    public function addRecord(EspnSeasonTypeTeamRecord $record): static
    {
        if (!$this->records->contains($record)) {
            $this->records->add($record);
            $record->setSeasonType($this);
        }

        return $this;
    }

    public function removeRecord(EspnSeasonTypeTeamRecord $record): static
    {
        if ($this->records->removeElement($record)) {
            // set the owning side to null (unless already changed)
            if ($record->getSeasonType() === $this) {
                $record->setSeasonType(null);
            }
        }

        return $this;
    }

    public function addOrReplaceRecord(EspnSeasonTypeTeamRecord $newRecord): static
    {
        foreach ($this->records as $key => $existingRecord) {
            if ($existingRecord->getId() !== null && $existingRecord->getId() === $newRecord->getId()) {
                if ($existingRecord !== $newRecord) {
                    $this->records->set($key, $newRecord);
                    $newRecord->setSeasonType($this);
                }
                return $this;
            }
        }

        return $this->addRecord($newRecord);
    }
}
