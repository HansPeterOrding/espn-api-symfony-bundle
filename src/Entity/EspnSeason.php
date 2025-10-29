<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiClient\Dto\EspnSeason as EspnSeasonDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnSeasonTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonRepository;

#[ORM\Entity(repositoryClass: EspnSeasonRepository::class)]
class EspnSeason
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $displayName = null;

    #[ORM\Column]
    private ?int $half = null;

    /**
     * @var Collection<int, EspnScheduleEvent>
     */
    #[ORM\OneToMany(mappedBy: 'season', targetEntity: EspnScheduleEvent::class)]
    private Collection $scheduleEvents;

    public function __construct()
    {
        $this->scheduleEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getType(): ?EspnSeasonTypeEnum
    {
        return $this->type;
    }

    public function setType(EspnSeasonTypeEnum $type): static
    {
        $this->type = $type;

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

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getHalf(): ?int
    {
        return $this->half;
    }

    public function setHalf(int $half): static
    {
        $this->half = $half;

        return $this;
    }

    /**
     * @return Collection<int, EspnScheduleEvent>
     */
    public function getScheduleEvents(): Collection
    {
        return $this->scheduleEvents;
    }

    public function addScheduleEvent(EspnScheduleEvent $scheduleEvent): static
    {
        if (!$this->scheduleEvents->contains($scheduleEvent)) {
            $this->scheduleEvents->add($scheduleEvent);
            $scheduleEvent->setSeason($this);
        }

        return $this;
    }

    public function removeScheduleEvent(EspnScheduleEvent $scheduleEvent): static
    {
        if ($this->scheduleEvents->removeElement($scheduleEvent)) {
            // set the owning side to null (unless already changed)
            if ($scheduleEvent->getSeason() === $this) {
                $scheduleEvent->setSeason(null);
            }
        }

        return $this;
    }

    public function buildFindByCriteriaFromDto(EspnSeasonDto $espnSeasonDto): array
    {
        /** @todo: implement */
        return [
            'year' => $espnSeasonDto->getYear(),
        ];
    }
}
