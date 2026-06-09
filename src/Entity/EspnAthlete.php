<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnAthleteRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnAthlete as EspnAthleteDto;

#[ORM\Entity(repositoryClass: EspnAthleteRepository::class)]
#[ORM\Table(name: 'easb_espn_athlete')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_espn_athlete_season', columns: ['espn_id', 'season_id'])]
class EspnAthlete
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
    private ?string $guid = null;

    #[ORM\Column(nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $alternateIds = null;

    #[ORM\Column(nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(nullable: true)]
    private ?string $fullName = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(nullable: true)]
    private ?string $shortName = null;

    #[ORM\Column(type: 'decimal', precision: 8, scale: 2, nullable: true)]
    private ?string $weight = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayWeight = null;

    #[ORM\Column(type: 'decimal', precision: 8, scale: 2, nullable: true)]
    private ?string $height = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayHeight = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\Column(nullable: true)]
    private ?string $dateOfBirth = null;

    #[ORM\Column(nullable: true)]
    private ?int $debutYear = null;

    #[ORM\Column(nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?string $jersey = null;

    #[ORM\Column(nullable: true)]
    private ?string $headshotHref = null;

    #[ORM\Column(nullable: true)]
    private ?string $headshotAlt = null;

    #[ORM\Column(nullable: true)]
    private ?string $draftDisplayText = null;

    #[ORM\Column(nullable: true)]
    private ?int $draftRound = null;

    #[ORM\Column(nullable: true)]
    private ?int $draftYear = null;

    #[ORM\Column(nullable: true)]
    private ?int $draftSelection = null;

    #[ORM\Column(nullable: true)]
    private ?string $draftTeamReference = null;

    #[ORM\Embedded(class: EspnAddress::class, columnPrefix: 'birth_place_')]
    private EspnAddress $birthPlace;

    #[ORM\Column(nullable: true)]
    private ?bool $linked = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    #[ORM\Column(nullable: true)]
    private ?int $experienceYears = null;

    #[ORM\Embedded(class: EspnAthleteStatus::class, columnPrefix: 'status_')]
    private EspnAthleteStatus $status;

    #[ORM\Column(nullable: true)]
    private ?string $collegeReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $collegeAthleteReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $notesReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $contractsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $contractReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $statisticsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $projectionsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $eventLogReference = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $injuriesReferences = [];

    #[ORM\Column(nullable: true)]
    private ?string $positionReference = null;

    #[ORM\ManyToOne(targetEntity: EspnPosition::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnPosition $position = null;

    #[ORM\ManyToOne(targetEntity: EspnTeam::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnTeam $team = null;

    #[ORM\ManyToOne(targetEntity: EspnSeason::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnSeason $season = null;

    /**
     * @var Collection<int, EspnNote>
     */
    #[ORM\OneToMany(
        mappedBy: 'athlete',
        targetEntity: EspnNote::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $notes;

    /**
     * @var Collection<int, EspnInjury>
     */
    #[ORM\ManyToMany(
        targetEntity: EspnInjury::class,
        mappedBy: 'athletes'
    )]
    private Collection $injuries;

    public function __construct()
    {
        $this->birthPlace = new EspnAddress();
        $this->status = new EspnAthleteStatus();
        $this->notes = new ArrayCollection();
        $this->injuries = new ArrayCollection();
    }

    public static function buildFindByCriteriaFromDto(EspnAthleteDto $dto, EspnSeason $season): array
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

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid): static
    {
        $this->uid = $uid;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): static
    {
        $this->fullName = $fullName;
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

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): static
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight(?string $weight): static
    {
        $this->weight = $weight;
        return $this;
    }

    public function getDisplayWeight(): ?string
    {
        return $this->displayWeight;
    }

    public function setDisplayWeight(?string $displayWeight): static
    {
        $this->displayWeight = $displayWeight;
        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(?string $height): static
    {
        $this->height = $height;
        return $this;
    }

    public function getDisplayHeight(): ?string
    {
        return $this->displayHeight;
    }

    public function setDisplayHeight(?string $displayHeight): static
    {
        $this->displayHeight = $displayHeight;
        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;
        return $this;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?string $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    public function getDebutYear(): ?int
    {
        return $this->debutYear;
    }

    public function setDebutYear(?int $debutYear): static
    {
        $this->debutYear = $debutYear;
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

    public function getJersey(): ?string
    {
        return $this->jersey;
    }

    public function setJersey(?string $jersey): static
    {
        $this->jersey = $jersey;
        return $this;
    }

    public function getHeadshotHref(): ?string
    {
        return $this->headshotHref;
    }

    public function setHeadshotHref(?string $headshotHref): static
    {
        $this->headshotHref = $headshotHref;
        return $this;
    }

    public function getHeadshotAlt(): ?string
    {
        return $this->headshotAlt;
    }

    public function setHeadshotAlt(?string $headshotAlt): static
    {
        $this->headshotAlt = $headshotAlt;
        return $this;
    }

    public function getDraftDisplayText(): ?string
    {
        return $this->draftDisplayText;
    }

    public function setDraftDisplayText(?string $draftDisplayText): static
    {
        $this->draftDisplayText = $draftDisplayText;
        return $this;
    }

    public function getDraftRound(): ?int
    {
        return $this->draftRound;
    }

    public function setDraftRound(?int $draftRound): static
    {
        $this->draftRound = $draftRound;
        return $this;
    }

    public function getDraftYear(): ?int
    {
        return $this->draftYear;
    }

    public function setDraftYear(?int $draftYear): static
    {
        $this->draftYear = $draftYear;
        return $this;
    }

    public function getDraftSelection(): ?int
    {
        return $this->draftSelection;
    }

    public function setDraftSelection(?int $draftSelection): static
    {
        $this->draftSelection = $draftSelection;
        return $this;
    }

    public function getDraftTeamReference(): ?string
    {
        return $this->draftTeamReference;
    }

    public function setDraftTeamReference(?string $draftTeamReference): static
    {
        $this->draftTeamReference = $draftTeamReference;
        return $this;
    }

    public function getBirthPlace(): EspnAddress
    {
        return $this->birthPlace;
    }

    public function setBirthPlace(EspnAddress $birthPlace): static
    {
        $this->birthPlace = $birthPlace;
        return $this;
    }

    public function getLinked(): ?bool
    {
        return $this->linked;
    }

    public function setLinked(?bool $linked): static
    {
        $this->linked = $linked;
        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;
        return $this;
    }

    public function getExperienceYears(): ?int
    {
        return $this->experienceYears;
    }

    public function setExperienceYears(?int $experienceYears): static
    {
        $this->experienceYears = $experienceYears;
        return $this;
    }

    public function getStatus(): EspnAthleteStatus
    {
        return $this->status;
    }

    public function setStatus(EspnAthleteStatus $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getCollegeReference(): ?string
    {
        return $this->collegeReference;
    }

    public function setCollegeReference(?string $v): static
    {
        $this->collegeReference = $v;
        return $this;
    }

    public function getCollegeAthleteReference(): ?string
    {
        return $this->collegeAthleteReference;
    }

    public function setCollegeAthleteReference(?string $v): static
    {
        $this->collegeAthleteReference = $v;
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

    public function getContractsReference(): ?string
    {
        return $this->contractsReference;
    }

    public function setContractsReference(?string $v): static
    {
        $this->contractsReference = $v;
        return $this;
    }

    public function getContractReference(): ?string
    {
        return $this->contractReference;
    }

    public function setContractReference(?string $v): static
    {
        $this->contractReference = $v;
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

    public function getProjectionsReference(): ?string
    {
        return $this->projectionsReference;
    }

    public function setProjectionsReference(?string $v): static
    {
        $this->projectionsReference = $v;
        return $this;
    }

    public function getEventLogReference(): ?string
    {
        return $this->eventLogReference;
    }

    public function setEventLogReference(?string $v): static
    {
        $this->eventLogReference = $v;
        return $this;
    }

    public function getInjuriesReferences(): array
    {
        return $this->injuriesReferences ?? [];
    }

    public function setInjuriesReferences(?array $v): static
    {
        $this->injuriesReferences = $v ?? [];
        return $this;
    }

    public function getPositionReference(): ?string
    {
        return $this->positionReference;
    }

    public function setPositionReference(?string $v): static
    {
        $this->positionReference = $v;
        return $this;
    }

    public function getPosition(): ?EspnPosition
    {
        return $this->position;
    }

    public function setPosition(?EspnPosition $position): static
    {
        $this->position = $position;
        return $this;
    }

    public function getTeam(): ?EspnTeam
    {
        return $this->team;
    }

    public function setTeam(?EspnTeam $team): static
    {
        $this->team = $team;
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

    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(EspnNote $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setAthlete($this);
        }
        return $this;
    }

    public function removeNote(EspnNote $note): static
    {
        if ($this->notes->removeElement($note)) {
            if ($note->getAthlete() === $this) {
                $note->setAthlete(null);
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
                    $newNote->setAthlete($this);
                }
                return $this;
            }
        }
        return $this->addNote($newNote);
    }

    public function getInjuries(): Collection
    {
        return $this->injuries;
    }

    public function addInjury(EspnInjury $injury): static
    {
        if (!$this->injuries->contains($injury)) {
            $this->injuries->add($injury);
            $injury->addAthlete($this);
        }
        return $this;
    }

    public function removeInjury(EspnInjury $injury): static
    {
        if ($this->injuries->removeElement($injury)) {
            $injury->removeAthlete($this);
        }
        return $this;
    }

    public function removeAllInjuries(): static
    {
        foreach ($this->injuries as $injury) {
            $this->removeInjury($injury);
        }
        return $this;
    }

    public function addOrReplaceInjury(EspnInjury $newInjury): static
    {
        foreach ($this->injuries as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newInjury->getId()) {
                if ($existing !== $newInjury) {
                    $this->injuries->set($key, $newInjury);
                    $newInjury->setAthlete($this);
                }
                return $this;
            }
        }
        return $this->addInjury($newInjury);
    }
}
