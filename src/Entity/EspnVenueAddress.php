<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use App\Repository\HansPeterOrding\EspnApiSymfonyBundle\Entity\EspnVenueAddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EspnVenueAddress
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $country = null;

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): EspnVenueAddress
    {
        $this->city = $city;
        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): EspnVenueAddress
    {
        $this->state = $state;
        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): EspnVenueAddress
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): EspnVenueAddress
    {
        $this->country = $country;
        return $this;
    }
}
