<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeTeamRecord as EspnSeasonTypeTeamRecordDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeTeamRecordRepository;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeTeamRecordStat;

#[ORM\Entity(repositoryClass: EspnSeasonTypeTeamRecordRepository::class)]
#[ORM\Table(name: 'easb_espn_season_type_team_record')]
#[ORM\UniqueConstraint(name: 'uniq_easb_espn_season_type_team_records', columns: ['type', 'season_team_id', 'season_type_id'])]
class EspnSeasonTypeTeamRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $displayValue = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 25, scale: 19, nullable: true)]
    private ?string $value = null;

    /**
     * @var Collection<int, EspnSeasonTypeTeamRecordStat>
     */
    #[ORM\OneToMany(mappedBy: 'record', targetEntity: EspnSeasonTypeTeamRecordStat::class, orphanRemoval: true)]
    private Collection $stats;

    #[ORM\ManyToOne(inversedBy: 'records')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnSeasonTeam $seasonTeam = null;

    #[ORM\ManyToOne(inversedBy: 'records')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnSeasonType $seasonType = null;

    public function __construct()
    {
        $this->stats = new ArrayCollection();
    }

    public function buildFindByCriteriaFromDto(EspnSeasonType $espnSeasonType, EspnSeasonTeam $espnSeasonTeam, EspnSeasonTypeTeamRecordDto $espnSeasonTypeTeamRecordDto): array
    {
        return [
            'seasonTeam' => $espnSeasonTeam,
            'seasonType' => $espnSeasonType,
            'type' => $espnSeasonTypeTeamRecordDto->getType()
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

    public function getValueAsFloat(): ?float
    {
        return $this->value !== null ? (float)$this->value : null;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection<int, EspnSeasonTypeTeamRecordStat>
     */
    public function getStats(): Collection
    {
        return $this->stats;
    }

    public function addStat(EspnSeasonTypeTeamRecordStat $stat): static
    {
        if (!$this->stats->contains($stat)) {
            $this->stats->add($stat);
            $stat->setRecord($this);
        }

        return $this;
    }

    public function removeStat(EspnSeasonTypeTeamRecordStat $stat): static
    {
        if ($this->stats->removeElement($stat)) {
            // set the owning side to null (unless already changed)
            if ($stat->getRecord() === $this) {
                $stat->setRecord(null);
            }
        }

        return $this;
    }

    public function addOrReplaceStat(EspnSeasonTypeTeamRecordStat $newStat): static
    {
        foreach ($this->stats as $key => $existingStat) {
            if ($existingStat->getId() !== null && $existingStat->getId() === $newStat->getId()) {
                if ($existingStat !== $newStat) {
                    $this->stats->set($key, $newStat);
                    $newStat->setRecord($this);
                }
                return $this;
            }
        }

        return $this->addStat($newStat);
    }

    public function getSeasonTeam(): ?EspnSeasonTeam
    {
        return $this->seasonTeam;
    }

    public function setSeasonTeam(?EspnSeasonTeam $seasonTeam): static
    {
        $this->seasonTeam = $seasonTeam;

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
