<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnTeamRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnTeam as EspnTeamDto;

#[ORM\Entity(repositoryClass: EspnTeamRepository::class)]
#[ORM\Table(name: 'easb_espn_team')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_espn_team', columns: ['espn_id'])]
class EspnTeam
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $guid = null;

    #[ORM\Column(nullable: true)]
    private ?string $uid = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $alternateIds = null;

    #[ORM\Column(nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?string $location = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $nickname = null;

    #[ORM\Column(nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(nullable: true)]
    private ?string $shortDisplayName = null;

    #[ORM\Column(nullable: true)]
    private ?string $color = null;

    #[ORM\Column(nullable: true)]
    private ?string $alternateColor = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isAllStar = null;

    #[ORM\Column(nullable: true)]
    private ?string $recordReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $oddsRecordsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $athletesReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $venueReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $groupsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $ranksReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $statisticsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $leadersReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $injuriesReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $notesReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $againstTheSpreadRecordsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $awardsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $franchiseReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $depthChartsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $projectionReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $eventsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $transactionsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $coachesReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $attendanceReference = null;

    #[ORM\ManyToOne(targetEntity: EspnVenue::class, inversedBy: 'teams')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnVenue $venue = null;

    #[ORM\OneToOne(targetEntity: EspnFranchise::class, inversedBy: 'team')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnFranchise $franchise = null;

    /**
     * @var Collection<int, EspnImage>
     */
    #[ORM\OneToMany(
        mappedBy: 'team',
        targetEntity: EspnImage::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $images;

    /**
     * @var Collection<int, EspnNote>
     */
    #[ORM\OneToMany(
        mappedBy: 'team',
        targetEntity: EspnNote::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $notes;

    /**
     * @var Collection<int, EspnSeasonGroup>
     */
    #[ORM\ManyToMany(
        targetEntity: EspnSeasonGroup::class,
        inversedBy: 'teams'
    )]
    #[ORM\JoinTable(name: 'easb_espn_season_group_to_espn_team')]
    private Collection $seasonGroups;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->seasonGroups = new ArrayCollection();
    }

    public static function buildFindByCriteriaFromDto(EspnTeamDto $dto): array
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

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(?string $guid): static
    {
        $this->guid = $guid;
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

    public function getAlternateIds(): ?array
    {
        return $this->alternateIds;
    }

    public function setAlternateIds(?array $alternateIds): static
    {
        $this->alternateIds = $alternateIds;
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

    public function getAlternateColor(): ?string
    {
        return $this->alternateColor;
    }

    public function setAlternateColor(?string $alternateColor): static
    {
        $this->alternateColor = $alternateColor;
        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIsAllStar(): ?bool
    {
        return $this->isAllStar;
    }

    public function setIsAllStar(?bool $isAllStar): static
    {
        $this->isAllStar = $isAllStar;
        return $this;
    }

    public function getRecordReference(): ?string
    {
        return $this->recordReference;
    }

    public function setRecordReference(?string $v): static
    {
        $this->recordReference = $v;
        return $this;
    }

    public function getOddsRecordsReference(): ?string
    {
        return $this->oddsRecordsReference;
    }

    public function setOddsRecordsReference(?string $v): static
    {
        $this->oddsRecordsReference = $v;
        return $this;
    }

    public function getAthletesReference(): ?string
    {
        return $this->athletesReference;
    }

    public function setAthletesReference(?string $v): static
    {
        $this->athletesReference = $v;
        return $this;
    }

    public function getVenueReference(): ?string
    {
        return $this->venueReference;
    }

    public function setVenueReference(?string $v): static
    {
        $this->venueReference = $v;
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

    public function getRanksReference(): ?string
    {
        return $this->ranksReference;
    }

    public function setRanksReference(?string $v): static
    {
        $this->ranksReference = $v;
        return $this;
    }

    public function getStatisticsReference(): ?string
    {
        return $this->statisticsReference;
    }

    public function setStatisticsReference(?string $v): static
    {
        $this->statisticsReference = $v;
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

    public function getInjuriesReference(): ?string
    {
        return $this->injuriesReference;
    }

    public function setInjuriesReference(?string $v): static
    {
        $this->injuriesReference = $v;
        return $this;
    }

    public function getNotesReference(): ?string
    {
        return $this->notesReference;
    }

    public function setNotesReference(?string $v): static
    {
        $this->notesReference = $v;
        return $this;
    }

    public function getAgainstTheSpreadRecordsReference(): ?string
    {
        return $this->againstTheSpreadRecordsReference;
    }

    public function setAgainstTheSpreadRecordsReference(?string $v): static
    {
        $this->againstTheSpreadRecordsReference = $v;
        return $this;
    }

    public function getAwardsReference(): ?string
    {
        return $this->awardsReference;
    }

    public function setAwardsReference(?string $v): static
    {
        $this->awardsReference = $v;
        return $this;
    }

    public function getFranchiseReference(): ?string
    {
        return $this->franchiseReference;
    }

    public function setFranchiseReference(?string $v): static
    {
        $this->franchiseReference = $v;
        return $this;
    }

    public function getDepthChartsReference(): ?string
    {
        return $this->depthChartsReference;
    }

    public function setDepthChartsReference(?string $v): static
    {
        $this->depthChartsReference = $v;
        return $this;
    }

    public function getProjectionReference(): ?string
    {
        return $this->projectionReference;
    }

    public function setProjectionReference(?string $v): static
    {
        $this->projectionReference = $v;
        return $this;
    }

    public function getEventsReference(): ?string
    {
        return $this->eventsReference;
    }

    public function setEventsReference(?string $v): static
    {
        $this->eventsReference = $v;
        return $this;
    }

    public function getTransactionsReference(): ?string
    {
        return $this->transactionsReference;
    }

    public function setTransactionsReference(?string $v): static
    {
        $this->transactionsReference = $v;
        return $this;
    }

    public function getCoachesReference(): ?string
    {
        return $this->coachesReference;
    }

    public function setCoachesReference(?string $v): static
    {
        $this->coachesReference = $v;
        return $this;
    }

    public function getAttendanceReference(): ?string
    {
        return $this->attendanceReference;
    }

    public function setAttendanceReference(?string $v): static
    {
        $this->attendanceReference = $v;
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

    public function getFranchise(): ?EspnFranchise
    {
        return $this->franchise;
    }

    public function setFranchise(?EspnFranchise $franchise): static
    {
        $this->franchise = $franchise;
        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(EspnImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setTeam($this);
        }
        return $this;
    }

    public function removeImage(EspnImage $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getTeam() === $this) {
                $image->setTeam(null);
            }
        }
        return $this;
    }

    public function removeAllImages(): static
    {
        foreach ($this->images as $image) {
            $this->removeImage($image);
        }
        return $this;
    }

    public function addOrReplaceImage(EspnImage $newImage): static
    {
        foreach ($this->images as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newImage->getId()) {
                if ($existing !== $newImage) {
                    $this->images->set($key, $newImage);
                    $newImage->setTeam($this);
                }
                return $this;
            }
        }
        return $this->addImage($newImage);
    }

    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(EspnNote $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setTeam($this);
        }
        return $this;
    }

    public function removeNote(EspnNote $note): static
    {
        if ($this->notes->removeElement($note)) {
            if ($note->getTeam() === $this) {
                $note->setTeam(null);
            }
        }
        return $this;
    }

    public function removeAllNotes(): static
    {
        foreach ($this->notes as $note) {
            $this->removeNote($note);
        }
        return $this;
    }

    public function addOrReplaceNote(EspnNote $newNote): static
    {
        foreach ($this->notes as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newNote->getId()) {
                if ($existing !== $newNote) {
                    $this->notes->set($key, $newNote);
                    $newNote->setTeam($this);
                }
                return $this;
            }
        }
        return $this->addNote($newNote);
    }

    public function getSeasonGroups(): Collection
    {
        return $this->seasonGroups;
    }

    public function addSeasonGroup(EspnSeasonGroup $seasonGroup): static
    {
        if (!$this->seasonGroups->contains($seasonGroup)) {
            $this->seasonGroups->add($seasonGroup);
        }
        return $this;
    }

    public function removeSeasonGroup(EspnSeasonGroup $seasonGroup): static
    {
        $this->seasonGroups->removeElement($seasonGroup);
        return $this;
    }

    public function removeAllSeasonGroups(): static
    {
        foreach ($this->seasonGroups as $seasonGroup) {
            $this->removeSeasonGroup($seasonGroup);
        }
        return $this;
    }

    public function addOrReplaceSeasonGroup(EspnSeasonGroup $seasonGroup): static
    {
        return $this->addSeasonGroup($seasonGroup);
    }
}
