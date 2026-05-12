<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiClient\Dto\EspnVenue as EspnVenueDto;
use HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnImage;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnVenueRepository;

#[ORM\Entity(repositoryClass: EspnVenueRepository::class)]
#[ORM\Table(name: 'easb_espn_venue')]
class EspnVenue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $espnId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $guid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullName = null;

    #[ORM\Embedded(class: EspnVenueAddressEmbeddable::class, columnPrefix: 'address_')]
    private ?EspnVenueAddressEmbeddable $address = null;

    #[ORM\Column(nullable: true)]
    private ?bool $grass = null;

    #[ORM\Column(nullable: true)]
    private ?bool $indoor = null;

    /**
     * @var Collection<int, EspnImage>
     */
    #[ORM\OneToMany(mappedBy: 'venue', targetEntity: EspnImage::class)]
    private Collection $images;

    /**
     * @var Collection<int, EspnSeasonTeam>
     */
    #[ORM\OneToMany(mappedBy: 'venue', targetEntity: EspnSeasonTeam::class)]
    private Collection $teams;

    /**
     * @var Collection<int, EspnFranchise>
     */
    #[ORM\OneToMany(mappedBy: 'venue', targetEntity: EspnFranchise::class)]
    private Collection $franchises;

    public function __construct()
    {
        $this->address = new EspnVenueAddressEmbeddable();
        $this->images = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->franchises = new ArrayCollection();
    }

    public function buildFindByCriteriaFromDto(EspnVenueDto $espnVenueDto): array
    {
        return [
            'espnId' => $espnVenueDto->getId(),
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

    public function getAddress(): ?EspnVenueAddressEmbeddable
    {
        return $this->address;
    }

    public function setAddress(?EspnVenueAddressEmbeddable $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function isGrass(): ?bool
    {
        return $this->grass;
    }

    public function setGrass(?bool $grass): static
    {
        $this->grass = $grass;

        return $this;
    }

    public function isIndoor(): ?bool
    {
        return $this->indoor;
    }

    public function setIndoor(?bool $indoor): static
    {
        $this->indoor = $indoor;

        return $this;
    }

    /**
     * @return Collection<int, EspnImage>
     */
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
            // set the owning side to null (unless already changed)
            if ($image->getVenue() === $this) {
                $image->setVenue(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EspnSeasonTeam>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(EspnSeasonTeam $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->setVenue($this);
        }

        return $this;
    }

    public function removeTeam(EspnSeasonTeam $team): static
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getVenue() === $this) {
                $team->setVenue(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EspnFranchise>
     */
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
            // set the owning side to null (unless already changed)
            if ($franchise->getVenue() === $this) {
                $franchise->setVenue(null);
            }
        }

        return $this;
    }

    public function addOrReplaceFranchise(EspnFranchise $newFranchise): static
    {
        foreach ($this->franchises as $key => $existingFranchise) {
            if ($existingFranchise->getId() !== null && $existingFranchise->getId() === $newFranchise->getId()) {
                if ($existingFranchise !== $newFranchise) {
                    $this->franchises->set($key, $newFranchise);
                    $newFranchise->setVenue($this);
                }
                return $this;
            }
        }

        return $this->addFranchise($newFranchise);
    }
}
