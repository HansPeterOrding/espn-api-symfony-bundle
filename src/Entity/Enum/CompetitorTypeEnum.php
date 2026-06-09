<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum CompetitorTypeEnum: string
{
    case Team = 'team';
    case Athlete = 'athlete';
}
