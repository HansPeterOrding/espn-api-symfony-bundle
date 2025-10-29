<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use HansPeterOrding\EspnApiClient\Dto\EspnVenue as EspnVenueDto;
use HansPeterOrding\EspnApiSymfonyBundle\Repository\EspnVenueRepository;

#[ORM\Entity(repositoryClass: EspnVenueRepository::class)]
class EspnVenue
{
    #[ORM\Id()]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $venueId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $guid = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Embedded(class: EspnVenueAddress::class, columnPrefix: 'address_')]
    private ?EspnVenueAddress $address;

    #[ORM\Column(nullable: true)]
    private ?bool $grass = null;

    #[ORM\Column(nullable: true)]
    private ?bool $indoor = null;

    /**
     * @var Collection<int, EspnFranchise>
     */
    #[ORM\OneToMany(mappedBy: 'venue', targetEntity: EspnFranchise::class)]
    private Collection $franchises;

    public function __construct()
    {
        $this->address = new EspnVenueAddress();
        $this->franchises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): EspnVenue
    {
        $this->id = $id;
        return $this;
    }

    public function getVenueId(): ?string
    {
        return $this->venueId;
    }

    public function setVenueId(?string $venueId): EspnVenue
    {
        $this->venueId = $venueId;
        return $this;
    }

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(?string $guid): EspnVenue
    {
        $this->guid = $guid;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): EspnVenue
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getAddress(): ?EspnVenueAddress
    {
        return $this->address;
    }

    public function setAddress(?EspnVenueAddress $address): EspnVenue
    {
        $this->address = $address;
        return $this;
    }

    public function getGrass(): ?bool
    {
        return $this->grass;
    }

    public function setGrass(?bool $grass): EspnVenue
    {
        $this->grass = $grass;
        return $this;
    }

    public function getIndoor(): ?bool
    {
        return $this->indoor;
    }

    public function setIndoor(?bool $indoor): EspnVenue
    {
        $this->indoor = $indoor;
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

    public function buildFindByCriteriaFromDto(EspnVenueDto $espnVenueDto): array
    {
        return [
            'venueId' => $espnVenueDto->getId(),
        ];
    }
}
