<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\CompetitorHomeAwayEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\CompetitorTypeEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnCompetitorRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnCompetitor as EspnCompetitorDto;

#[ORM\Entity(repositoryClass: EspnCompetitorRepository::class)]
#[ORM\Table(name: 'easb_espn_competitor')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_espn_competitor', columns: ['espn_id', 'competition_id'])]
class EspnCompetitor
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $uid = null;

    #[ORM\Column(nullable: true, enumType: CompetitorTypeEnum::class)]
    private ?CompetitorTypeEnum $type = null;

    #[ORM\Column(name: 'display_order', nullable: true)]
    private ?int $displayOrder = null;

    #[ORM\Column(nullable: true, enumType: CompetitorHomeAwayEnum::class)]
    private ?CompetitorHomeAwayEnum $homeAway = null;

    #[ORM\Column(nullable: true)]
    private ?bool $winner = null;

    #[ORM\Column(nullable: true)]
    private ?string $scoreReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $linescoresReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $rosterReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $statisticsReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $leadersReference = null;

    #[ORM\Column(nullable: true)]
    private ?string $recordReference = null;

    #[ORM\ManyToOne(targetEntity: EspnCompetition::class, inversedBy: 'competitors')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnCompetition $competition = null;

    #[ORM\ManyToOne(targetEntity: EspnTeam::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnTeam $team = null;

    #[ORM\OneToOne(
        mappedBy: 'competitor',
        targetEntity: EspnScore::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private ?EspnScore $score = null;

    public static function buildFindByCriteriaFromDto(EspnCompetitorDto $dto, EspnCompetition $competition): array
    {
        return [
            'espnId' => $dto->getId(),
            'competition' => $competition,
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

    public function getType(): ?CompetitorTypeEnum
    {
        return $this->type;
    }

    public function setType(?CompetitorTypeEnum $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(?int $displayOrder): static
    {
        $this->displayOrder = $displayOrder;
        return $this;
    }

    public function getHomeAway(): ?CompetitorHomeAwayEnum
    {
        return $this->homeAway;
    }

    public function setHomeAway(?CompetitorHomeAwayEnum $homeAway): static
    {
        $this->homeAway = $homeAway;
        return $this;
    }

    public function getWinner(): ?bool
    {
        return $this->winner;
    }

    public function setWinner(?bool $winner): static
    {
        $this->winner = $winner;
        return $this;
    }

    public function getScoreReference(): ?string
    {
        return $this->scoreReference;
    }

    public function setScoreReference(?string $v): static
    {
        $this->scoreReference = $v;
        return $this;
    }

    public function getLinescoresReference(): ?string
    {
        return $this->linescoresReference;
    }

    public function setLinescoresReference(?string $v): static
    {
        $this->linescoresReference = $v;
        return $this;
    }

    public function getRosterReference(): ?string
    {
        return $this->rosterReference;
    }

    public function setRosterReference(?string $v): static
    {
        $this->rosterReference = $v;
        return $this;
    }

    public function getStatisticsReference(): ?string
    {
        return $this->statisticsReference;
    }

    public function setStatisticsReference(?string $v): static
    {
        $this->statisticsReference = $v;
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

    public function getRecordReference(): ?string
    {
        return $this->recordReference;
    }

    public function setRecordReference(?string $v): static
    {
        $this->recordReference = $v;
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

    public function getTeam(): ?EspnTeam
    {
        return $this->team;
    }

    public function setTeam(?EspnTeam $team): static
    {
        $this->team = $team;
        return $this;
    }

    public function getScore(): ?EspnScore
    {
        return $this->score;
    }

    public function setScore(?EspnScore $score): static
    {
        if ($score !== null && $score->getCompetitor() !== $this) {
            $score->setCompetitor($this);
        }
        $this->score = $score;
        return $this;
    }
}
