<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeWeek as EspnSeasonTypeWeekDto;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeWeekRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EspnSeasonTypeWeekRepository::class)]
#[ORM\Table(name: 'easb_espn_season_type_week')]
class EspnSeasonTypeWeek
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $number = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $endDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'weeks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnSeasonType $type = null;

    public function buildFindByCriteriaFromDto(EspnSeasonType $espnSeasonType, EspnSeasonTypeWeekDto $espnSeasonTypeWeekDto): array
    {
        return [
            'type' => $espnSeasonType,
            'number' => $espnSeasonTypeWeekDto->getNumber(),
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

    public function setNumber(?int $number): EspnSeasonTypeWeek
    {
        $this->number = $number;
        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): EspnSeasonTypeWeek
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): EspnSeasonTypeWeek
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): EspnSeasonTypeWeek
    {
        $this->text = $text;
        return $this;
    }

    public function getType(): ?EspnSeasonType
    {
        return $this->type;
    }

    public function setType(?EspnSeasonType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
