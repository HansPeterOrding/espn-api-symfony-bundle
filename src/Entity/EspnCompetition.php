<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitionRepository;

#[ORM\Entity(repositoryClass: EspnCompetitionRepository::class)]
class EspnCompetition
{
    #[ORM\Column]
    private ?string $id = null;

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

    #[ORM\Embedded(class: EspnVenue::class, columnPrefix: 'venue_')]
    private ?EspnVenue $venue = null;

    public function __construct()
    {
        $this->type = new EspnCompetitionType();
        $this->venue = new EspnVenue();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): EspnCompetition
    {
        $this->id = $id;
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

    public function setVenue(?EspnVenue $venue): EspnCompetition
    {
        $this->venue = $venue;
        return $this;
    }
}
