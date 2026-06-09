<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class EspnCompetitionFormat
{
    #[ORM\Embedded(class: EspnCompetitionFormatPeriod::class, columnPrefix: 'format_regulation_')]
    private EspnCompetitionFormatPeriod $regulation;

    #[ORM\Embedded(class: EspnCompetitionFormatPeriod::class, columnPrefix: 'format_overtime_')]
    private EspnCompetitionFormatPeriod $overtime;

    #[ORM\Embedded(class: EspnCompetitionFormatPeriod::class, columnPrefix: 'format_sudden_death_')]
    private EspnCompetitionFormatPeriod $suddenDeath;

    public function __construct()
    {
        $this->regulation = new EspnCompetitionFormatPeriod();
        $this->overtime = new EspnCompetitionFormatPeriod();
        $this->suddenDeath = new EspnCompetitionFormatPeriod();
    }

    public function getRegulation(): EspnCompetitionFormatPeriod
    {
        return $this->regulation;
    }

    public function setRegulation(EspnCompetitionFormatPeriod $regulation): static
    {
        $this->regulation = $regulation;
        return $this;
    }

    public function getOvertime(): EspnCompetitionFormatPeriod
    {
        return $this->overtime;
    }

    public function setOvertime(EspnCompetitionFormatPeriod $overtime): static
    {
        $this->overtime = $overtime;
        return $this;
    }

    public function getSuddenDeath(): EspnCompetitionFormatPeriod
    {
        return $this->suddenDeath;
    }

    public function setSuddenDeath(EspnCompetitionFormatPeriod $suddenDeath): static
    {
        $this->suddenDeath = $suddenDeath;
        return $this;
    }
}
