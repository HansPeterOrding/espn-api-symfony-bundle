<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum EspnSeasonTypeEnum: int
{
    case PRE = 1;
    case REGULAR = 2;
    case POST = 3;
    case OFF = 4;

    public function getName(): string
    {
        return match ($this) {
            self::PRE => 'Preseason',
            self::REGULAR => 'Regular Season',
            self::POST => 'Postseason',
            self::OFF => 'Off Season',
        };
    }

    public function getAbbreviation(): string
    {
        return match ($this) {
            self::PRE => 'pre',
            self::REGULAR => 'reg',
            self::POST => 'off',
            self::OFF => 'post',
        };
    }
}
