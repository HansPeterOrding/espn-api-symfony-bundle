<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum EspnCompetitorHomeAwayEnum: string
{
    case HOME = 'home';
    case AWAY = 'away';
}
