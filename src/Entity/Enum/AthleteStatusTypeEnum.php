<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum AthleteStatusTypeEnum: string
{
    case Active = 'active';
    case Injured = 'injured';
    case InactiveList = 'inactivelist';
    case PracticeSquad = 'practicesquad';
}
