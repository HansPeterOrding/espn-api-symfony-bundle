<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EspnVenue
{
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $guid = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Embedded(class: EspnVenueAddress::class, columnPrefix: 'address_')]
    private ?EspnVenueAddress $address = null;

    #[ORM\Column(nullable: true)]
    private ?bool $grass = null;

    #[ORM\Column(nullable: true)]
    private ?bool $indoor = null;

    public function __construct()
    {
        $this->address = new EspnVenueAddress();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): EspnVenue
    {
        $this->id = $id;
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
}
