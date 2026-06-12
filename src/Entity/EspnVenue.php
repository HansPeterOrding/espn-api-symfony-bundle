<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\Trait\SyncTimestampsTrait;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnVenueRepository;
use HansPeterOrding\EspnApiClient\Dto\EspnVenue as EspnVenueDto;

#[ORM\Entity(repositoryClass: EspnVenueRepository::class)]
#[ORM\Table(name: 'easb_espn_venue')]
#[ORM\HasLifecycleCallbacks]
class EspnVenue
{
    use SyncTimestampsTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'bigint')]
    private ?int $id = null;

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(nullable: true)]
    private ?string $guid = null;

    #[ORM\Column(nullable: true)]
    private ?string $fullName = null;

    #[ORM\Embedded(class: EspnAddress::class, columnPrefix: 'address_')]
    private EspnAddress $address;

    #[ORM\Column(nullable: true)]
    private ?bool $grass = null;

    #[ORM\Column(nullable: true)]
    private ?bool $indoor = null;

    /**
     * @var Collection<int, EspnImage>
     */
    #[ORM\OneToMany(
        targetEntity: EspnImage::class,
        mappedBy: 'venue',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $images;

    /**
     * @var Collection<int, EspnTeam>
     */
    #[ORM\OneToMany(
        targetEntity: EspnTeam::class,
        mappedBy: 'venue',
        cascade: ['persist'],
        orphanRemoval: false
    )]
    private Collection $teams;

    /**
     * @var Collection<int, EspnFranchise>
     */
    #[ORM\OneToMany(
        targetEntity: EspnFranchise::class,
        mappedBy: 'venue',
        cascade: ['persist'],
        orphanRemoval: false
    )]
    private Collection $franchises;

    public function __construct()
    {
        $this->address = new EspnAddress();
        $this->images = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->franchises = new ArrayCollection();
    }

    public static function buildFindByCriteriaFromDto(EspnVenueDto $dto): array
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

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(?string $guid): static
    {
        $this->guid = $guid;
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

    public function getAddress(): EspnAddress
    {
        return $this->address;
    }

    public function setAddress(EspnAddress $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getGrass(): ?bool
    {
        return $this->grass;
    }

    public function setGrass(?bool $grass): static
    {
        $this->grass = $grass;
        return $this;
    }

    public function getIndoor(): ?bool
    {
        return $this->indoor;
    }

    public function setIndoor(?bool $indoor): static
    {
        $this->indoor = $indoor;
        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(EspnImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setVenue($this);
        }
        return $this;
    }

    public function removeImage(EspnImage $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getVenue() === $this) {
                $image->setVenue(null);
            }
        }
        return $this;
    }

    public function removeAllImages(): static
    {
        foreach ($this->images as $image) {
            $this->removeImage($image);
        }
        return $this;
    }

    public function addOrReplaceImage(EspnImage $newImage): static
    {
        foreach ($this->images as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newImage->getId()) {
                if ($existing !== $newImage) {
                    $this->images->set($key, $newImage);
                    $newImage->setVenue($this);
                }
                return $this;
            }
        }
        return $this->addImage($newImage);
    }

    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(EspnTeam $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->setVenue($this);
        }
        return $this;
    }

    public function removeTeam(EspnTeam $team): static
    {
        if ($this->teams->removeElement($team)) {
            if ($team->getVenue() === $this) {
                $team->setVenue(null);
            }
        }
        return $this;
    }

    public function removeAllTeams(): static
    {
        foreach ($this->teams as $team) {
            $this->removeTeam($team);
        }
        return $this;
    }

    public function addOrReplaceTeam(EspnTeam $newTeam): static
    {
        foreach ($this->teams as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newTeam->getId()) {
                if ($existing !== $newTeam) {
                    $this->teams->set($key, $newTeam);
                    $newTeam->setVenue($this);
                }
                return $this;
            }
        }
        return $this->addTeam($newTeam);
    }

    public function getFranchises(): Collection
    {
        return $this->franchises;
    }

    public function addFranchise(EspnFranchise $franchise): static
    {
        if (!$this->franchises->contains($franchise)) {
            $this->franchises->add($franchise);
            $franchise->setVenue($this);
        }
        return $this;
    }

    public function removeFranchise(EspnFranchise $franchise): static
    {
        if ($this->franchises->removeElement($franchise)) {
            if ($franchise->getVenue() === $this) {
                $franchise->setVenue(null);
            }
        }
        return $this;
    }

    public function removeAllFranchises(): static
    {
        foreach ($this->franchises as $franchise) {
            $this->removeFranchise($franchise);
        }
        return $this;
    }

    public function addOrReplaceFranchise(EspnFranchise $newFranchise): static
    {
        foreach ($this->franchises as $key => $existing) {
            if ($existing->getId() !== null && $existing->getId() === $newFranchise->getId()) {
                if ($existing !== $newFranchise) {
                    $this->franchises->set($key, $newFranchise);
                    $newFranchise->setVenue($this);
                }
                return $this;
            }
        }
        return $this->addFranchise($newFranchise);
    }
}
