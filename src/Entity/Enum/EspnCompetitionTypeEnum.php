<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum EspnCompetitionTypeEnum: string
{
    case STANDARD = 'standard';

    public function getText(): string
    {
        return match ($this) {
            self::STANDARD => 'Standard'
        };
    }

    public function getAbbreviation(): string
    {
        return match ($this) {
            static::STANDARD => 'STD',
        };
    }

    public function getSlug(): string
    {
        return match ($this) {
            static::STANDARD => 'standard',
        };
    }
}
