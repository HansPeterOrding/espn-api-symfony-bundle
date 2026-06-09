<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum SeasonTypeEnum: int
{
    case Preseason = 1;
    case Regular = 2;
    case Postseason = 3;
    case Offseason = 4;
}
