<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitionRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetition as EspnCompetitionDto;

#[ORM\Entity(repositoryClass: EspnCompetitionRepository::class)]
#[ORM\Table(name: 'easb_espn_competition')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(columns: ['espn_id'], name: 'idx_espn_competition_espn_id')]
class EspnCompetition
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $guid = null;

    #[ORM\Column(nullable: true)]
    private ?string $uid = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $attendance = null;

    #[ORM\Column(nullable: true)]
    private ?bool $timeValid = null;

    #[ORM\Column(nullable: true)]
    private ?bool $dateValid = null;

    #[ORM\Column(nullable: true)]
    private ?bool $neutralSite = null;

    #[ORM\Column(nullable: true)]
    private ?bool $divisionCompetition = null;

    #[ORM\Column(nullable: true)]
    private ?bool $conferenceCompetition = null;

    #[ORM\Column(nullable: true)]
    private ?bool $previewAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $recapAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $boxscoreAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $lineupAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $gamecastAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $playByPlayAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $conversationAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $commentaryAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $pickcenterAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $summaryAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $liveAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ticketsAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $highlightsAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $onWatchESPN = null;

    #[ORM\Column(nullable: true)]
    private ?bool $recent = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bracketAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $wallclockAvailable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $hasDefensiveStats = null;

    #[ORM\Embedded(class: EspnCompetitionType::class, columnPrefix: 'type_')]
    private EspnCompetitionType $type;

    #[ORM\Embedded(class: EspnCompetitionFormat::class, columnPrefix: 'format_')]
    private EspnCompetitionFormat $format;

    #[ORM\OneToOne(targetEntity: EspnCompetitionStatus::class, mappedBy: 'competition')]
    private ?EspnCompetitionStatus $status = null;

    #[ORM\Embedded(class: EspnSource::class, columnPrefix: 'game_source_')]
    private EspnSource $gameSource;

    #[ORM\Embedded(class: EspnSource::class, columnPrefix: 'boxscore_source_')]
    private EspnSource $boxscoreSource;

    #[ORM\Embedded(class: EspnSource::class, columnPrefix: 'play_by_play_source_')]
    private EspnSource $playByPlaySource;

    #[ORM\Embedded(class: EspnSource::class, columnPrefix: 'linescore_source_')]
    private EspnSource $linescoreSource;

    #[ORM\Embedded(class: EspnSource::class, columnPrefix: 'stats_source_')]
    private EspnSource $statsSource;

    #[ORM\Column(nullable: true)]
    private ?string $venueReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $statusReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $situationReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $oddsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $broadcastsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $officialsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $leadersReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $predicatorReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $probabilitiesReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $powerIndexesReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $relevancyReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $drivesReference = null;

    #[ORM\ManyToOne(targetEntity: EspnEvent::class, inversedBy: 'competitions')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnEvent $event = null;

    #[ORM\ManyToOne(targetEntity: EspnVenue::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnVenue $venue = null;

    /**
     * @var Collection<int, EspnCompetitor>
     */
    #[ORM\OneToMany(
        mappedBy: 'competition',
        targetEntity: EspnCompetitor::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $competitors;

    /**
     * @var Collection<int, EspnOfficial>
     */
    #[ORM\OneToMany(
        mappedBy: 'competition',
        targetEntity: EspnOfficial::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $officials;

    public function __construct()
    {
        $this->type = new EspnCompetitionType();
        $this->format = new EspnCompetitionFormat();
        $this->gameSource = new EspnSource();
        $this->boxscoreSource = new EspnSource();
        $this->playByPlaySource = new EspnSource();
        $this->linescoreSource = new EspnSource();
        $this->statsSource = new EspnSource();
        $this->competitors = new ArrayCollection();
        $this->officials = new ArrayCollection();
    }

    public static function buildFindByCriteriaFromDto(EspnCompetitionDto $dto): array
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

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?DateTimeImmutable $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getAttendance(): ?int
    {
        return $this->attendance;
    }

    public function setAttendance(?int $attendance): static
    {
        $this->attendance = $attendance;
        return $this;
    }

    public function getTimeValid(): ?bool
    {
        return $this->timeValid;
    }

    public function setTimeValid(?bool $v): static
    {
        $this->timeValid = $v;
        return $this;
    }

    public function getDateValid(): ?bool
    {
        return $this->dateValid;
    }

    public function setDateValid(?bool $v): static
    {
        $this->dateValid = $v;
        return $this;
    }

    public function getNeutralSite(): ?bool
    {
        return $this->neutralSite;
    }

    public function setNeutralSite(?bool $v): static
    {
        $this->neutralSite = $v;
        return $this;
    }

    public function getDivisionCompetition(): ?bool
    {
        return $this->divisionCompetition;
    }

    public function setDivisionCompetition(?bool $v): static
    {
        $this->divisionCompetition = $v;
        return $this;
    }

    public function getConferenceCompetition(): ?bool
    {
        return $this->conferenceCompetition;
    }

    public function setConferenceCompetition(?bool $v): static
    {
        $this->conferenceCompetition = $v;
        return $this;
    }

    public function getPreviewAvailable(): ?bool
    {
        return $this->previewAvailable;
    }

    public function setPreviewAvailable(?bool $v): static
    {
        $this->previewAvailable = $v;
        return $this;
    }

    public function getRecapAvailable(): ?bool
    {
        return $this->recapAvailable;
    }

    public function setRecapAvailable(?bool $v): static
    {
        $this->recapAvailable = $v;
        return $this;
    }

    public function getBoxscoreAvailable(): ?bool
    {
        return $this->boxscoreAvailable;
    }

    public function setBoxscoreAvailable(?bool $v): static
    {
        $this->boxscoreAvailable = $v;
        return $this;
    }

    public function getLineupAvailable(): ?bool
    {
        return $this->lineupAvailable;
    }

    public function setLineupAvailable(?bool $v): static
    {
        $this->lineupAvailable = $v;
        return $this;
    }

    public function getGamecastAvailable(): ?bool
    {
        return $this->gamecastAvailable;
    }

    public function setGamecastAvailable(?bool $v): static
    {
        $this->gamecastAvailable = $v;
        return $this;
    }

    public function getPlayByPlayAvailable(): ?bool
    {
        return $this->playByPlayAvailable;
    }

    public function setPlayByPlayAvailable(?bool $v): static
    {
        $this->playByPlayAvailable = $v;
        return $this;
    }

    public function getConversationAvailable(): ?bool
    {
        return $this->conversationAvailable;
    }

    public function setConversationAvailable(?bool $v): static
    {
        $this->conversationAvailable = $v;
        return $this;
    }

    public function getCommentaryAvailable(): ?bool
    {
        return $this->commentaryAvailable;
    }

    public function setCommentaryAvailable(?bool $v): static
    {
        $this->commentaryAvailable = $v;
        return $this;
    }

    public function getPickcenterAvailable(): ?bool
    {
        return $this->pickcenterAvailable;
    }

    public function setPickcenterAvailable(?bool $v): static
    {
        $this->pickcenterAvailable = $v;
        return $this;
    }

    public function getSummaryAvailable(): ?bool
    {
        return $this->summaryAvailable;
    }

    public function setSummaryAvailable(?bool $v): static
    {
        $this->summaryAvailable = $v;
        return $this;
    }

    public function getLiveAvailable(): ?bool
    {
        return $this->liveAvailable;
    }

    public function setLiveAvailable(?bool $v): static
    {
        $this->liveAvailable = $v;
        return $this;
    }

    public function getTicketsAvailable(): ?bool
    {
        return $this->ticketsAvailable;
    }

    public function setTicketsAvailable(?bool $v): static
    {
        $this->ticketsAvailable = $v;
        return $this;
    }

    public function getHighlightsAvailable(): ?bool
    {
        return $this->highlightsAvailable;
    }

    public function setHighlightsAvailable(?bool $v): static
    {
        $this->highlightsAvailable = $v;
        return $this;
    }

    public function getOnWatchESPN(): ?bool
    {
        return $this->onWatchESPN;
    }

    public function setOnWatchESPN(?bool $v): static
    {
        $this->onWatchESPN = $v;
        return $this;
    }

    public function getRecent(): ?bool
    {
        return $this->recent;
    }

    public function setRecent(?bool $v): static
    {
        $this->recent = $v;
        return $this;
    }

    public function getBracketAvailable(): ?bool
    {
        return $this->bracketAvailable;
    }

    public function setBracketAvailable(?bool $v): static
    {
        $this->bracketAvailable = $v;
        return $this;
    }

    public function getWallclockAvailable(): ?bool
    {
        return $this->wallclockAvailable;
    }

    public function setWallclockAvailable(?bool $v): static
    {
        $this->wallclockAvailable = $v;
        return $this;
    }

    public function getHasDefensiveStats(): ?bool
    {
        return $this->hasDefensiveStats;
    }

    public function setHasDefensiveStats(?bool $v): static
    {
        $this->hasDefensiveStats = $v;
        return $this;
    }

    public function getType(): EspnCompetitionType
    {
        return $this->type;
    }

    public function setType(EspnCompetitionType $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getFormat(): EspnCompetitionFormat
    {
        return $this->format;
    }

    public function setFormat(EspnCompetitionFormat $format): static
    {
        $this->format = $format;
        return $this;
    }

    public function getStatus(): ?EspnCompetitionStatus
    {
        return $this->status;
    }

    public function setStatus(?EspnCompetitionStatus $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getGameSource(): EspnSource
    {
        return $this->gameSource;
    }

    public function setGameSource(EspnSource $gameSource): static
    {
        $this->gameSource = $gameSource;
        return $this;
    }

    public function getBoxscoreSource(): EspnSource
    {
        return $this->boxscoreSource;
    }

    public function setBoxscoreSource(EspnSource $boxscoreSource): static
    {
        $this->boxscoreSource = $boxscoreSource;
        return $this;
    }

    public function getPlayByPlaySource(): EspnSource
    {
        return $this->playByPlaySource;
    }

    public function setPlayByPlaySource(EspnSource $playByPlaySource): static
    {
        $this->playByPlaySource = $playByPlaySource;
        return $this;
    }

    public function getLinescoreSource(): EspnSource
    {
        return $this->linescoreSource;
    }

    public function setLinescoreSource(EspnSource $linescoreSource): static
    {
        $this->linescoreSource = $linescoreSource;
        return $this;
    }

    public function getStatsSource(): EspnSource
    {
        return $this->statsSource;
    }

    public function setStatsSource(EspnSource $statsSource): static
    {
        $this->statsSource = $statsSource;
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

    public function getStatusReference(): ?string
    {
        return $this->statusReference;
    }

    public function setStatusReference(?string $v): static
    {
        $this->statusReference = $v;
        return $this;
    }

    public function getSituationReference(): ?string
    {
        return $this->situationReference;
    }

    public function setSituationReference(?string $v): static
    {
        $this->situationReference = $v;
        return $this;
    }

    public function getOddsReference(): ?string
    {
        return $this->oddsReference;
    }

    public function setOddsReference(?string $v): static
    {
        $this->oddsReference = $v;
        return $this;
    }

    public function getBroadcastsReference(): ?string
    {
        return $this->broadcastsReference;
    }

    public function setBroadcastsReference(?string $v): static
    {
        $this->broadcastsReference = $v;
        return $this;
    }

    public function getOfficialsReference(): ?string
    {
        return $this->officialsReference;
    }

    public function setOfficialsReference(?string $v): static
    {
        $this->officialsReference = $v;
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

    public function getPredicatorReference(): ?string
    {
        return $this->predicatorReference;
    }

    public function setPredicatorReference(?string $v): static
    {
        $this->predicatorReference = $v;
        return $this;
    }

    public function getProbabilitiesReference(): ?string
    {
        return $this->probabilitiesReference;
    }

    public function setProbabilitiesReference(?string $v): static
    {
        $this->probabilitiesReference = $v;
        return $this;
    }

    public function getPowerIndexesReference(): ?string
    {
        return $this->powerIndexesReference;
    }

    public function setPowerIndexesReference(?string $v): static
    {
        $this->powerIndexesReference = $v;
        return $this;
    }

    public function getRelevancyReference(): ?string
    {
        return $this->relevancyReference;
    }

    public function setRelevancyReference(?string $v): static
    {
        $this->relevancyReference = $v;
        return $this;
    }

    public function getDrivesReference(): ?string
    {
        return $this->drivesReference;
    }

    public function setDrivesReference(?string $v): static
    {
        $this->drivesReference = $v;
        return $this;
    }

    public function getEvent(): ?EspnEvent
    {
        return $this->event;
    }

    public function setEvent(?EspnEvent $event): static
    {
        $this->event = $event;
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

    public function getCompetitors(): Collection
    {
        return $this->competitors;
    }

    public function addCompetitor(EspnCompetitor $competitor): static
    {
        if (!$this->competitors->contains($competitor)) {
            $this->competitors->add($competitor);
            $competitor->setCompetition($this);
        }
        return $this;
    }

    public function removeCompetitor(EspnCompetitor $competitor): static
    {
        if ($this->competitors->removeElement($competitor)) {
            if ($competitor->getCompetition() === $this) {
                $competitor->setCompetition(null);
            }
        }
        return $this;
    }

    public function removeAllCompetitors(): static
    {
        foreach ($this->competitors as $competitor) {
            $this->removeCompetitor($competitor);
        }
        return $this;
    }

    public function addOrReplaceCompetitor(EspnCompetitor $newCompetitor): static
    {
        foreach ($this->competitors as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newCompetitor->getId()) {
                if ($existing !== $newCompetitor) {
                    $this->competitors->set($key, $newCompetitor);
                    $newCompetitor->setCompetition($this);
                }
                return $this;
            }
        }
        return $this->addCompetitor($newCompetitor);
    }

    public function getOfficials(): Collection
    {
        return $this->officials;
    }

    public function addOfficial(EspnOfficial $official): static
    {
        if (!$this->officials->contains($official)) {
            $this->officials->add($official);
            $official->setCompetition($this);
        }
        return $this;
    }

    public function removeOfficial(EspnOfficial $official): static
    {
        if ($this->officials->removeElement($official)) {
            if ($official->getCompetition() === $this) {
                $official->setCompetition(null);
            }
        }
        return $this;
    }

    public function removeAllOfficials(): static
    {
        foreach ($this->officials as $official) {
            $this->removeOfficial($official);
        }
        return $this;
    }

    public function addOrReplaceOfficial(EspnOfficial $newOfficial): static
    {
        foreach ($this->officials as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newOfficial->getId()) {
                if ($existing !== $newOfficial) {
                    $this->officials->set($key, $newOfficial);
                    $newOfficial->setCompetition($this);
                }
                return $this;
            }
        }
        return $this->addOfficial($newOfficial);
    }
}
