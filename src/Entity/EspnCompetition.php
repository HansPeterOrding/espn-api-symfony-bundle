<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitionRepository;

#[ORM\Entity(repositoryClass: EspnCompetitionRepository::class)]
class EspnCompetition
{
    #[ORM\Id()]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $competitionId = null;

    #[ORM\Column]
    private ?DateTime $date = null;

    #[ORM\Column]
    private ?int $attendance = null;

    #[ORM\Embedded(class: EspnCompetitionType::class, columnPrefix: 'type_')]
    private ?EspnCompetitionType $type = null;

    #[ORM\Column]
    private ?bool $typeValid = null;

    #[ORM\Column]
    private ?bool $neutralSite = null;

    #[ORM\Column]
    private ?bool $boxscoreAvailable = null;

    #[ORM\Column]
    private ?bool $ticketsAvailable = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnVenue $venue = null;

    /**
     * @var Collection<int, EspnCompetitor>
     */
    #[ORM\OneToMany(targetEntity: EspnCompetitor::class, mappedBy: 'competition')]
    private Collection $competitors;

    #[ORM\Column]
    private array $notes = [];

    /**
     * @var Collection<int, EspnBroadcast>
     */
    #[ORM\OneToMany(targetEntity: EspnBroadcast::class, mappedBy: 'competition')]
    private Collection $broadcasts;

    #[ORM\Embedded(class: EspnCompetitionStatus::class, columnPrefix: 'status_')]
    private ?EspnCompetitionStatus $status;

    #[ORM\ManyToOne(inversedBy: 'competitions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnScheduleEvent $scheduleEvent = null;

    public function __construct()
    {
        $this->type = new EspnCompetitionType();
        $this->competitors = new ArrayCollection();
        $this->broadcasts = new ArrayCollection();
        $this->status = new EspnCompetitionStatus();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetitionId(): ?string
    {
        return $this->competitionId;
    }

    public function setCompetitionId(string $competitionId): static
    {
        $this->competitionId = $competitionId;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): EspnCompetition
    {
        $this->date = $date;
        return $this;
    }

    public function getAttendance(): ?int
    {
        return $this->attendance;
    }

    public function setAttendance(?int $attendance): EspnCompetition
    {
        $this->attendance = $attendance;
        return $this;
    }

    public function getType(): ?EspnCompetitionType
    {
        return $this->type;
    }

    public function setType(?EspnCompetitionType $type): EspnCompetition
    {
        $this->type = $type;
        return $this;
    }

    public function getTypeValid(): ?bool
    {
        return $this->typeValid;
    }

    public function setTypeValid(?bool $typeValid): EspnCompetition
    {
        $this->typeValid = $typeValid;
        return $this;
    }

    public function getNeutralSite(): ?bool
    {
        return $this->neutralSite;
    }

    public function setNeutralSite(?bool $neutralSite): EspnCompetition
    {
        $this->neutralSite = $neutralSite;
        return $this;
    }

    public function getBoxscoreAvailable(): ?bool
    {
        return $this->boxscoreAvailable;
    }

    public function setBoxscoreAvailable(?bool $boxscoreAvailable): EspnCompetition
    {
        $this->boxscoreAvailable = $boxscoreAvailable;
        return $this;
    }

    public function getTicketsAvailable(): ?bool
    {
        return $this->ticketsAvailable;
    }

    public function setTicketsAvailable(?bool $ticketsAvailable): EspnCompetition
    {
        $this->ticketsAvailable = $ticketsAvailable;
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

    /**
     * @return Collection<int, EspnCompetitor>
     */
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
            // set the owning side to null (unless already changed)
            if ($competitor->getCompetition() === $this) {
                $competitor->setCompetition(null);
            }
        }

        return $this;
    }

    public function getNotes(): array
    {
        return $this->notes;
    }

    public function setNotes(array $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return Collection<int, EspnBroadcast>
     */
    public function getBroadcasts(): Collection
    {
        return $this->broadcasts;
    }

    public function addBroadcast(EspnBroadcast $broadcast): static
    {
        if (!$this->broadcasts->contains($broadcast)) {
            $this->broadcasts->add($broadcast);
            $broadcast->setCompetition($this);
        }

        return $this;
    }

    public function removeBroadcast(EspnBroadcast $broadcast): static
    {
        if ($this->broadcasts->removeElement($broadcast)) {
            // set the owning side to null (unless already changed)
            if ($broadcast->getCompetition() === $this) {
                $broadcast->setCompetition(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getScheduleEvent(): ?EspnScheduleEvent
    {
        return $this->scheduleEvent;
    }

    public function setScheduleEvent(?EspnScheduleEvent $scheduleEvent): static
    {
        $this->scheduleEvent = $scheduleEvent;

        return $this;
    }
}
