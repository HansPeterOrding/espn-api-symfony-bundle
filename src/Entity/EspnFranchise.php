<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnFranchiseRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnFranchise as EspnFranchiseDto;

#[ORM\Entity(repositoryClass: EspnFranchiseRepository::class)]
#[ORM\Table(name: 'easb_espn_franchise')]
#[ORM\HasLifecycleCallbacks]
class EspnFranchise
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $uid = null;

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?string $location = null;

    #[ORM\Column(nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?string $nickname = null;

    #[ORM\Column(nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column(nullable: true)]
    private ?string $displayName = null;

    #[ORM\Column(nullable: true)]
    private ?string $shortDisplayName = null;

    #[ORM\Column(nullable: true)]
    private ?string $color = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActive = null;

    #[ORM\Column(nullable: true)]
    private ?string $venueReference = null;

    #[ORM\ManyToOne(targetEntity: EspnVenue::class, inversedBy: 'franchises')]
    #[ORM\JoinColumn(nullable: true)]
    private ?EspnVenue $venue = null;

    #[ORM\OneToOne(targetEntity: EspnTeam::class, mappedBy: 'franchise')]
    private ?EspnTeam $team = null;

    public static function buildFindByCriteriaFromDto(EspnFranchiseDto $dto): array
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

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(?string $uid): static
    {
        $this->uid = $uid;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;
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

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): static
    {
        $this->nickname = $nickname;
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;
        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getVenueReference(): ?string
    {
        return $this->venueReference;
    }

    public function setVenueReference(?string $v): static
    {
        $this->venueReference = $v;
        return $this;
    }

    public function getVenue(): ?EspnVenue
    {
        return $this->venue;
    }

    public function setVenue(?EspnVenue $venue): static
    {
        $this->venue = $venue;
        return $this;
    }

    public function getTeam(): ?EspnTeam
    {
        return $this->team;
    }

    public function setTeam(?EspnTeam $team): static
    {
        $this->team = $team;
        if (null !== $team && $team->getFranchise() !== $this) {
            $team->setFranchise($this);
        }
        return $this;
    }
}
