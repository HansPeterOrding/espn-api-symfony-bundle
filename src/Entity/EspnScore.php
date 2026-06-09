<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnScoreRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnScore as EspnScoreDto;

#[ORM\Entity(repositoryClass: EspnScoreRepository::class)]
#[ORM\Table(name: 'easb_espn_score')]
#[ORM\HasLifecycleCallbacks]
class EspnScore
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayValue = null;

    #[ORM\Column(nullable: true)]
    private ?bool $winner = null;

    #[ORM\Embedded(class: EspnSource::class, columnPrefix: 'source_')]
    private EspnSource $source;

    #[ORM\OneToOne(inversedBy: 'score', targetEntity: EspnCompetitor::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnCompetitor $competitor = null;

    public function __construct()
    {
        $this->source = new EspnSource();
    }

    public static function buildFindByCriteriaFromDto(EspnScoreDto $dto, EspnCompetitor $competitor): array
    {
        return [
            'competitor' => $competitor,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getWinner(): ?bool
    {
        return $this->winner;
    }

    public function setWinner(?bool $winner): static
    {
        $this->winner = $winner;
        return $this;
    }

    public function getSource(): EspnSource
    {
        return $this->source;
    }

    public function setSource(EspnSource $source): static
    {
        $this->source = $source;
        return $this;
    }

    public function getCompetitor(): ?EspnCompetitor
    {
        return $this->competitor;
    }

    public function setCompetitor(?EspnCompetitor $competitor): static
    {
        $this->competitor = $competitor;
        return $this;
    }
}
