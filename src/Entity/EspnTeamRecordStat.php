<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

class EspnTeamRecordStat
{
    private ?string $name = null;

    private ?float $value = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }
}
