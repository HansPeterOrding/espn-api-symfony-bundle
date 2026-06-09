<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnRecordRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnRecord as EspnRecordDto;

#[ORM\Entity(repositoryClass: EspnRecordRepository::class)]
#[ORM\Table(name: 'easb_espn_record')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_espn_record', columns: ['espn_id', 'team_id', 'season_type_id'])]
class EspnRecord
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(nullable: true)]
    private ?string $shortDisplayName = null;

    #[ORM\Column(nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column(nullable: true)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayValue = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 4, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $stats = null;

    #[ORM\ManyToOne(targetEntity: EspnTeam::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnTeam $team = null;

    #[ORM\ManyToOne(targetEntity: EspnSeason::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnSeason $season = null;

    #[ORM\ManyToOne(targetEntity: EspnSeasonType::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnSeasonType $seasonType = null;

    public static function buildFindByCriteriaFromDto(
        EspnRecordDto  $dto,
        EspnTeam       $team,
        EspnSeasonType $seasonType
    ): array
    {
        return [
            'espnId' => $dto->getId(),
            'team' => $team,
            'seasonType' => $seasonType,
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): static
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function getShortDisplayName(): ?string
    {
        return $this->shortDisplayName;
    }

    public function setShortDisplayName(?string $shortDisplayName): static
    {
        $this->shortDisplayName = $shortDisplayName;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): static
    {
        $this->abbreviation = $abbreviation;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = $summary;
        return $this;
    }

    public function getDisplayValue(): ?string
    {
        return $this->displayValue;
    }

    public function setDisplayValue(?string $displayValue): static
    {
        $this->displayValue = $displayValue;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function getStats(): ?array
    {
        return $this->stats;
    }

    public function setStats(?array $stats): static
    {
        $this->stats = $stats;
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
}
