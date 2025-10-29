<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum EspnSeasonTypeEnum: int
{
    case REGULAR = 2;

    public function getName(): string
    {
        return match ($this) {
            self::REGULAR => 'Regular Season',
        };
    }

    public function getAbbreviation(): string
    {
        return match ($this) {
            self::REGULAR => 'reg',
        };
    }
}
