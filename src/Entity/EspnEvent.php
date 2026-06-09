<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnEventRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnEvent as EspnEventDto;

#[ORM\Entity(repositoryClass: EspnEventRepository::class)]
#[ORM\Table(name: 'easb_espn_event')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Index(columns: ['espn_id'], name: 'idx_espn_event_espn_id')]
class EspnEvent
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $uid = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $date = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $shortName = null;

    #[ORM\Column(nullable: true)]
    private ?bool $timeValid = null;

    #[ORM\Column(nullable: true)]
    private ?string $leagueReference = null;

    #[ORM\ManyToOne(targetEntity: EspnSeason::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnSeason $season = null;

    #[ORM\ManyToOne(targetEntity: EspnSeasonType::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnSeasonType $seasonType = null;

    #[ORM\ManyToOne(targetEntity: EspnWeek::class, inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnWeek $week = null;

    /**
     * @var Collection<int, EspnCompetition>
     */
    #[ORM\OneToMany(
        mappedBy: 'event',
        targetEntity: EspnCompetition::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $competitions;

    public function __construct()
    {
        $this->competitions = new ArrayCollection();
    }

    public static function buildFindByCriteriaFromDto(EspnEventDto $dto): array
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
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

    public function getTimeValid(): ?bool
    {
        return $this->timeValid;
    }

    public function setTimeValid(?bool $timeValid): static
    {
        $this->timeValid = $timeValid;
        return $this;
    }

    public function getLeagueReference(): ?string
    {
        return $this->leagueReference;
    }

    public function setLeagueReference(?string $v): static
    {
        $this->leagueReference = $v;
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

    public function getSeasonType(): ?EspnSeasonType
    {
        return $this->seasonType;
    }

    public function setSeasonType(?EspnSeasonType $seasonType): static
    {
        $this->seasonType = $seasonType;
        return $this;
    }

    public function getWeek(): ?EspnWeek
    {
        return $this->week;
    }

    public function setWeek(?EspnWeek $week): static
    {
        $this->week = $week;
        return $this;
    }

    public function getCompetitions(): Collection
    {
        return $this->competitions;
    }

    public function addCompetition(EspnCompetition $competition): static
    {
        if (!$this->competitions->contains($competition)) {
            $this->competitions->add($competition);
            $competition->setEvent($this);
        }
        return $this;
    }

    public function removeCompetition(EspnCompetition $competition): static
    {
        if ($this->competitions->removeElement($competition)) {
            if ($competition->getEvent() === $this) {
                $competition->setEvent(null);
            }
        }
        return $this;
    }

    public function removeAllCompetitions(): static
    {
        foreach ($this->competitions as $competition) {
            $this->removeCompetition($competition);
        }
        return $this;
    }

    public function addOrReplaceCompetition(EspnCompetition $newCompetition): static
    {
        foreach ($this->competitions as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newCompetition->getId()) {
                if ($existing !== $newCompetition) {
                    $this->competitions->set($key, $newCompetition);
                    $newCompetition->setEvent($this);
                }
                return $this;
            }
        }
        return $this->addCompetition($newCompetition);
    }
}
