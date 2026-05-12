<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiClient\Dto\EspnSeasonTypeTeamRecordStat as EspnSeasonTypeTeamRecordStatDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnSeasonTypeTeamRecord;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnSeasonTypeTeamRecordStatRepository;

#[ORM\Entity(repositoryClass: EspnSeasonTypeTeamRecordStatRepository::class)]
#[ORM\Table(name: 'easb_espn_season_type_team_record_stat')]
class EspnSeasonTypeTeamRecordStat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shortDisplayName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 25, scale: 20, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $displayValue = null;

    #[ORM\ManyToOne(inversedBy: 'stats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EspnSeasonTypeTeamRecord $record = null;

    public function buildFindByCriteriaFromDto(EspnSeasonTypeTeamRecord $espnSeasonTypeTeamRecord, EspnSeasonTypeTeamRecordStatDto $espnSeasonTypeTeamRecordStatDto): array
    {
        return [
            'record' => $espnSeasonTypeTeamRecord,
            'type' => $espnSeasonTypeTeamRecordStatDto->getType()
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

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

    public function getRecord(): ?EspnSeasonTypeTeamRecord
    {
        return $this->record;
    }

    public function setRecord(?EspnSeasonTypeTeamRecord $record): static
    {
        $this->record = $record;

        return $this;
    }
}
