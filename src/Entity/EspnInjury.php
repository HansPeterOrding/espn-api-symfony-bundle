<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum\InjuryStatusEnum;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnInjuryRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnInjury as EspnInjuryDto;

#[ORM\Entity(repositoryClass: EspnInjuryRepository::class)]
#[ORM\Table(name: 'easb_espn_injury')]
#[ORM\HasLifecycleCallbacks]
class EspnInjury
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $longComment = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $shortComment = null;

    #[ORM\Column(nullable: true, enumType: InjuryStatusEnum::class)]
    private ?InjuryStatusEnum $status = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $date = null;

    #[ORM\Embedded(class: EspnSource::class, columnPrefix: 'source_')]
    private EspnSource $source;

    #[ORM\Embedded(class: EspnInjuryType::class, columnPrefix: 'type_')]
    private EspnInjuryType $type;

    /**
     * @var Collection<int, EspnAthlete>
     */
    #[ORM\ManyToMany(targetEntity: EspnAthlete::class, inversedBy: 'injuries')]
    #[ORM\JoinTable(name: 'easb_espn_injury_to_espn_athlete')]
    private Collection $athletes;

    #[ORM\ManyToOne(targetEntity: EspnTeam::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnTeam $team = null;

    public function __construct()
    {
        $this->source = new EspnSource();
        $this->type = new EspnInjuryType();
        $this->athletes = new ArrayCollection();
    }

    public static function buildFindByCriteriaFromDto(EspnInjuryDto $dto): array
    {
        return [
            'espnId' => $dto->getId(),
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

    public function getLongComment(): ?string
    {
        return $this->longComment;
    }

    public function setLongComment(?string $longComment): static
    {
        $this->longComment = $longComment;
        return $this;
    }

    public function getShortComment(): ?string
    {
        return $this->shortComment;
    }

    public function setShortComment(?string $shortComment): static
    {
        $this->shortComment = $shortComment;
        return $this;
    }

    public function getStatus(): ?InjuryStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?InjuryStatusEnum $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?DateTimeImmutable $date): static
    {
        $this->date = $date;
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

    public function getType(): EspnInjuryType
    {
        return $this->type;
    }

    public function setType(EspnInjuryType $type): static
    {
        $this->type = $type;
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

    public function getAthletes(): Collection
    {
        return $this->athletes;
    }

    public function addAthlete(EspnAthlete $athlete): static
    {
        if (!$this->athletes->contains($athlete)) {
            $this->athletes->add($athlete);
        }
        return $this;
    }

    public function removeAthlete(EspnAthlete $athlete): static
    {
        $this->athletes->removeElement($athlete);
        return $this;
    }
}
