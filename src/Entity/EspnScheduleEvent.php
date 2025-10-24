<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnScheduleEventRepository;

#[ORM\Entity(repositoryClass: EspnScheduleEventRepository::class)]
class EspnScheduleEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $scheduleEventId = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $shortName = null;

    #[ORM\ManyToOne(inversedBy: 'scheduleEvents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnSeason $season = null;

    #[ORM\Embedded(class: EspnWeek::class, columnPrefix: 'week_')]
    private ?EspnWeek $week = null;

    #[ORM\Column]
    private ?bool $timeValid = null;

    /**
     * @var Collection<int, EspnCompetition>
     */
    #[ORM\OneToMany(mappedBy: 'scheduleEvent', targetEntity: EspnCompetition::class)]
    private Collection $competitions;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnSchedule $schedule = null;

    public function __construct()
    {
        $this->week = new EspnWeek();
        $this->competitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScheduleEventId(): ?string
    {
        return $this->scheduleEventId;
    }

    public function setScheduleEventId(string $scheduleEventId): static
    {
        $this->scheduleEventId = $scheduleEventId;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): static
    {
        $this->shortName = $shortName;

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

    public function getWeek(): ?EspnWeek
    {
        return $this->week;
    }

    public function setWeek(?EspnWeek $week): EspnScheduleEvent
    {
        $this->week = $week;
        return $this;
    }

    public function isTimeValid(): ?bool
    {
        return $this->timeValid;
    }

    public function setTimeValid(bool $timeValid): static
    {
        $this->timeValid = $timeValid;

        return $this;
    }

    /**
     * @return Collection<int, EspnCompetition>
     */
    public function getCompetitions(): Collection
    {
        return $this->competitions;
    }

    public function addCompetition(EspnCompetition $competition): static
    {
        if (!$this->competitions->contains($competition)) {
            $this->competitions->add($competition);
            $competition->setScheduleEvent($this);
        }

        return $this;
    }

    public function removeCompetition(EspnCompetition $competition): static
    {
        if ($this->competitions->removeElement($competition)) {
            // set the owning side to null (unless already changed)
            if ($competition->getScheduleEvent() === $this) {
                $competition->setScheduleEvent(null);
            }
        }

        return $this;
    }

    public function getSchedule(): ?EspnSchedule
    {
        return $this->schedule;
    }

    public function setSchedule(?EspnSchedule $schedule): static
    {
        $this->schedule = $schedule;

        return $this;
    }
}
