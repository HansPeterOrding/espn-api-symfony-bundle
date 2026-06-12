<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnWeekRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnWeek as EspnWeekDto;

#[ORM\Entity(repositoryClass: EspnWeekRepository::class)]
#[ORM\Table(name: 'easb_espn_week')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_espn_week', columns: ['number', 'season_type_id'])]
class EspnWeek
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $number = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?string $text = null;

    #[ORM\Column(nullable: true)]
    private ?string $rankingsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $eventsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $talentpicksReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $qbrReference = null;

    #[ORM\ManyToOne(targetEntity: EspnSeasonType::class, inversedBy: 'weeks')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnSeasonType $seasonType = null;

    /**
     * @var Collection<int, EspnEvent>
     */
    #[ORM\OneToMany(
        targetEntity: EspnEvent::class,
        mappedBy: 'week',
        cascade: ['persist'],
        orphanRemoval: false
    )]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public static function buildFindByCriteriaFromDto(EspnWeekDto $dto, EspnSeasonType $seasonType): array
    {
        return [
            'number' => $dto->getNumber(),
            'seasonType' => $seasonType,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): static
    {
        $this->number = $number;
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;
        return $this;
    }

    public function getRankingsReference(): ?string
    {
        return $this->rankingsReference;
    }

    public function setRankingsReference(?string $v): static
    {
        $this->rankingsReference = $v;
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

    public function getTalentpicksReference(): ?string
    {
        return $this->talentpicksReference;
    }

    public function setTalentpicksReference(?string $v): static
    {
        $this->talentpicksReference = $v;
        return $this;
    }

    public function getQbrReference(): ?string
    {
        return $this->qbrReference;
    }

    public function setQbrReference(?string $v): static
    {
        $this->qbrReference = $v;
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

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(EspnEvent $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setWeek($this);
        }
        return $this;
    }

    public function removeEvent(EspnEvent $event): static
    {
        if ($this->events->removeElement($event)) {
            if ($event->getWeek() === $this) {
                $event->setWeek(null);
            }
        }
        return $this;
    }

    public function removeAllEvents(): static
    {
        foreach ($this->events as $event) {
            $this->removeEvent($event);
        }
        return $this;
    }

    public function addOrReplaceEvent(EspnEvent $newEvent): static
    {
        foreach ($this->events as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newEvent->getId()) {
                if ($existing !== $newEvent) {
                    $this->events->set($key, $newEvent);
                    $newEvent->setWeek($this);
                }
                return $this;
            }
        }
        return $this->addEvent($newEvent);
    }
}
