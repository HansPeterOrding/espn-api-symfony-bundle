<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnCompetitorHomeAwayEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\EspnCompetitorTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitorRepository;

#[ORM\Entity(repositoryClass: EspnCompetitorRepository::class)]
class EspnCompetitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $competitorId = null;

    #[ORM\Column(enumType: EspnCompetitorTypeEnum::class)]
    private ?EspnCompetitorTypeEnum $type = null;

    #[ORM\Column]
    private ?int $sortOrder = null;

    #[ORM\Column(enumType: EspnCompetitorHomeAwayEnum::class)]
    private ?EspnCompetitorHomeAwayEnum $homeAway = null;

    #[ORM\Column]
    private ?bool $winner = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnTeam $team = null;

    #[ORM\Embedded(class: EspnCompetitorScore::class, columnPrefix: 'score_')]
    private ?EspnCompetitorScore $score = null;

    #[ORM\ManyToOne(inversedBy: 'competitors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnCompetition $competition = null;

    public function __construct()
    {
        $this->score = new EspnCompetitorScore();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetitorId(): ?string
    {
        return $this->competitorId;
    }

    public function setCompetitorId(string $competitorId): static
    {
        $this->competitorId = $competitorId;

        return $this;
    }

    public function getType(): ?EspnCompetitorTypeEnum
    {
        return $this->type;
    }

    public function setType(EspnCompetitorTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getHomeAway(): ?EspnCompetitorHomeAwayEnum
    {
        return $this->homeAway;
    }

    public function setHomeAway(?EspnCompetitorHomeAwayEnum $homeAway): EspnCompetitor
    {
        $this->homeAway = $homeAway;
        return $this;
    }

    public function isWinner(): ?bool
    {
        return $this->winner;
    }

    public function setWinner(bool $winner): static
    {
        $this->winner = $winner;

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

    public function getScore(): ?EspnCompetitorScore
    {
        return $this->score;
    }

    public function setScore(?EspnCompetitorScore $score): EspnCompetitor
    {
        $this->score = $score;
        return $this;
    }

    public function getCompetition(): ?EspnCompetition
    {
        return $this->competition;
    }

    public function setCompetition(?EspnCompetition $competition): static
    {
        $this->competition = $competition;

        return $this;
    }
}
