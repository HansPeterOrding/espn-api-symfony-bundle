<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTeam as EspnSeasonTeamDto;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTeamRepository;

#[ORM\Entity(repositoryClass: EspnSeasonTeamRepository::class)]
#[ORM\Table(name: 'easb_espn_season_team')]
#[ORM\Index(name: 'idx_easb_espn_season_team_abbreviation', columns: ['abbreviation'])]
class EspnSeasonTeam
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $guid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uid = null;

    #[ORM\Column(nullable: true)]
    private ?array $alternateIds = null;

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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alternateColor = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isAllStar = null;

    /**
     * @var Collection<int, EspnImage>
     */
    #[ORM\OneToMany(mappedBy: 'team', targetEntity: EspnImage::class, cascade: ['remove', 'persist'], orphanRemoval: true)]
    private Collection $logos;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recordReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $oddsRecordsReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $athletesReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $venueReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $groupsReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ranksReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statisticsReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $leadersReference = null;

    /**
     * @var Collection<int, EspnLink>
     */
    #[ORM\OneToMany(mappedBy: 'team', targetEntity: EspnLink::class, cascade: ['remove', 'persist'], orphanRemoval: true)]
    private Collection $links;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $injuriesReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notesReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $againstTheSpreadRecordsReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $awardsReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $franchiseReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $depthChartsReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $projectionReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eventsReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transactionsReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coachesReference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attendanceReference = null;

    /**
     * @var Collection<int, EspnSeasonTypeTeamRecord>
     */
    #[ORM\OneToMany(mappedBy: 'seasonTeam', targetEntity: EspnSeasonTypeTeamRecord::class, orphanRemoval: true)]
    private Collection $records;

    public function __construct()
    {
        $this->logos = new ArrayCollection();
        $this->links = new ArrayCollection();
        $this->records = new ArrayCollection();
    }

    public function buildFindByCriteriaFromDto(EspnSeasonTeamDto $espnSeasonTeamDto): array
    {
        return [
            'espnId' => $espnSeasonTeamDto->getId()
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

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isAllStar(): ?bool
    {
        return $this->isAllStar;
    }

    public function setIsAllStar(?bool $isAllStar): static
    {
        $this->isAllStar = $isAllStar;

        return $this;
    }

    /**
     * @return Collection<int, EspnImage>
     */
    public function getLogos(): Collection
    {
        return $this->logos;
    }

    public function addLogo(EspnImage $logo): static
    {
        if (!$this->logos->contains($logo)) {
            $this->logos->add($logo);
            $logo->setTeam($this);
        }

        return $this;
    }

    public function removeLogo(EspnImage $logo): static
    {
        if ($this->logos->removeElement($logo)) {
            // set the owning side to null (unless already changed)
            if ($logo->getTeam() === $this) {
                $logo->setTeam(null);
            }
        }

        return $this;
    }

    public function removeAllLogos(): static
    {
        foreach($this->logos as $logo) {
            $this->removeLogo($logo);
        }

        return $this;
    }

    public function getRecordReference(): ?string
    {
        return $this->recordReference;
    }

    public function setRecordReference(?string $recordReference): static
    {
        $this->recordReference = $recordReference;

        return $this;
    }

    public function getOddsRecordsReference(): ?string
    {
        return $this->oddsRecordsReference;
    }

    public function setOddsRecordsReference(?string $oddsRecordsReference): static
    {
        $this->oddsRecordsReference = $oddsRecordsReference;

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

    public function getVenueReference(): ?string
    {
        return $this->venueReference;
    }

    public function setVenueReference(?string $venueReference): static
    {
        $this->venueReference = $venueReference;

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

    public function getRanksReference(): ?string
    {
        return $this->ranksReference;
    }

    public function setRanksReference(?string $ranksReference): static
    {
        $this->ranksReference = $ranksReference;

        return $this;
    }

    public function getStatisticsReference(): ?string
    {
        return $this->statisticsReference;
    }

    public function setStatisticsReference(?string $statisticsReference): static
    {
        $this->statisticsReference = $statisticsReference;

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

    /**
     * @return Collection<int, EspnLink>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(EspnLink $link): static
    {
        if (!$this->links->contains($link)) {
            $this->links->add($link);
            $link->setTeam($this);
        }

        return $this;
    }

    public function removeLink(EspnLink $link): static
    {
        if ($this->links->removeElement($link)) {
            // set the owning side to null (unless already changed)
            if ($link->getTeam() === $this) {
                $link->setTeam(null);
            }
        }

        return $this;
    }

    public function removeAllLinks(): static
    {
        foreach($this->links as $link) {
            $this->removeLink($link);
        }

        return $this;
    }

    public function getInjuriesReference(): ?string
    {
        return $this->injuriesReference;
    }

    public function setInjuriesReference(?string $injuriesReference): static
    {
        $this->injuriesReference = $injuriesReference;

        return $this;
    }

    public function getNotesReference(): ?string
    {
        return $this->notesReference;
    }

    public function setNotesReference(?string $notesReference): static
    {
        $this->notesReference = $notesReference;

        return $this;
    }

    public function getAgainstTheSpreadRecordsReference(): ?string
    {
        return $this->againstTheSpreadRecordsReference;
    }

    public function setAgainstTheSpreadRecordsReference(?string $againstTheSpreadRecordsReference): static
    {
        $this->againstTheSpreadRecordsReference = $againstTheSpreadRecordsReference;

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

    public function getFranchiseReference(): ?string
    {
        return $this->franchiseReference;
    }

    public function setFranchiseReference(?string $franchiseReference): static
    {
        $this->franchiseReference = $franchiseReference;

        return $this;
    }

    public function getDepthChartsReference(): ?string
    {
        return $this->depthChartsReference;
    }

    public function setDepthChartsReference(?string $depthChartsReference): static
    {
        $this->depthChartsReference = $depthChartsReference;

        return $this;
    }

    public function getProjectionReference(): ?string
    {
        return $this->projectionReference;
    }

    public function setProjectionReference(?string $projectionReference): static
    {
        $this->projectionReference = $projectionReference;

        return $this;
    }

    public function getEventsReference(): ?string
    {
        return $this->eventsReference;
    }

    public function setEventsReference(?string $eventsReference): static
    {
        $this->eventsReference = $eventsReference;

        return $this;
    }

    public function getTransactionsReference(): ?string
    {
        return $this->transactionsReference;
    }

    public function setTransactionsReference(?string $transactionsReference): static
    {
        $this->transactionsReference = $transactionsReference;

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

    public function getAttendanceReference(): ?string
    {
        return $this->attendanceReference;
    }

    public function setAttendanceReference(?string $attendanceReference): static
    {
        $this->attendanceReference = $attendanceReference;

        return $this;
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
            $record->setSeasonTeam($this);
        }

        return $this;
    }

    public function removeRecord(EspnSeasonTypeTeamRecord $record): static
    {
        if ($this->records->removeElement($record)) {
            // set the owning side to null (unless already changed)
            if ($record->getSeasonTeam() === $this) {
                $record->setSeasonTeam(null);
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
