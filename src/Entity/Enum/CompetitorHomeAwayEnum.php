<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum CompetitorHomeAwayEnum: string
{
    case Home = 'home';
    case Away = 'away';
}
