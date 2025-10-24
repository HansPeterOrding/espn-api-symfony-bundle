<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EspnCompetitorScore
{
    #[ORM\Column]
    private ?float $value = null;

    #[ORM\Column(length: 255)]
    private ?string $displayValue = null;

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getDisplayValue(): ?string
    {
        return $this->displayValue;
    }

    public function setDisplayValue(string $displayValue): static
    {
        $this->displayValue = $displayValue;

        return $this;
    }
}
