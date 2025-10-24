<?php

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

class EspnNote
{
    private ?string $type = null;

    private ?string $headline = null;

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): EspnNote
    {
        $this->type = $type;
        return $this;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function setHeadline(?string $headline): EspnNote
    {
        $this->headline = $headline;
        return $this;
    }
}
