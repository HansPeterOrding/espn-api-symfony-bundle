<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnScheduleStatusEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnScheduleRepository;

#[ORM\Entity(repositoryClass: EspnScheduleRepository::class)]
#[ORM\Index(columns: ['team_id', 'season_id'])]
class EspnSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $timestamp = null;

    #[ORM\Column(enumType: EspnScheduleStatusEnum::class)]
    private ?EspnScheduleStatusEnum $status = null;

    #[ORM\ManyToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnSeason $season = null;

    #[ORM\ManyToOne(inversedBy: 'schedules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnTeam $team = null;

    /**
     * @var Collection<int, EspnScheduleEvent>
     */
    #[ORM\OneToMany(mappedBy: 'schedule', targetEntity: EspnScheduleEvent::class, cascade: ['persist'])]
    private Collection $events;

    #[ORM\Column]
    private ?int $byeWeek = null;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTime
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTime $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getStatus(): ?EspnScheduleStatusEnum
    {
        return $this->status;
    }

    public function setStatus(EspnScheduleStatusEnum $status): static
    {
        $this->status = $status;

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

    public function getTeam(): ?EspnTeam
    {
        return $this->team;
    }

    public function setTeam(?EspnTeam $team): static
    {
        $this->team = $team;

        return $this;
    }

    /**
     * @return Collection<int, EspnScheduleEvent>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(EspnScheduleEvent $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setSchedule($this);
        }

        return $this;
    }

    public function addOrReplaceEvent(EspnScheduleEvent $event): static
    {
        foreach($this->events as $existingEvent) {
            if($existingEvent->getScheduleEventId() === $event->getScheduleEventId()) {
                $this->events->removeElement($existingEvent);
            }
        }

        $this->addEvent($event);

        return $this;
    }

    public function removeEvent(EspnScheduleEvent $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getSchedule() === $this) {
                $event->setSchedule(null);
            }
        }

        return $this;
    }

    public function getByeWeek(): ?int
    {
        return $this->byeWeek;
    }

    public function setByeWeek(int $byeWeek): static
    {
        $this->byeWeek = $byeWeek;

        return $this;
    }

    public function buildFindByCriteriaFromDto(EspnTeam $team): array
    {
        return [
            'team' => $team
        ];
    }
}
