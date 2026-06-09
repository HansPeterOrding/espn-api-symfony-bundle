<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnOfficialRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnOfficial as EspnOfficialDto;

#[ORM\Entity(repositoryClass: EspnOfficialRepository::class)]
#[ORM\Table(name: 'easb_espn_official')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_espn_official', columns: ['espn_id', 'competition_id'])]
class EspnOfficial
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(nullable: true)]
    private ?string $fullName = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(name: 'display_order', nullable: true)]
    private ?int $displayOrder = null;

    #[ORM\Embedded(class: EspnOfficialPosition::class, columnPrefix: 'position_')]
    private EspnOfficialPosition $position;

    #[ORM\ManyToOne(targetEntity: EspnCompetition::class, inversedBy: 'officials')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnCompetition $competition = null;

    public function __construct()
    {
        $this->position = new EspnOfficialPosition();
    }

    public static function buildFindByCriteriaFromDto(EspnOfficialDto $dto, EspnCompetition $competition): array
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): static
    {
        $this->fullName = $fullName;
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

    public function getDisplayOrder(): ?int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(?int $displayOrder): static
    {
        $this->displayOrder = $displayOrder;
        return $this;
    }

    public function getPosition(): EspnOfficialPosition
    {
        return $this->position;
    }

    public function setPosition(EspnOfficialPosition $position): static
    {
        $this->position = $position;
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
