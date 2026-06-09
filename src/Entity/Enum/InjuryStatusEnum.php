<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum InjuryStatusEnum: string
{
    case Active = 'Active';
    case Out = 'Out';
    case Questionable = 'Questionable';
    case Doubtful = 'Doubtful';
    case InjuredReserve = 'Injured Reserve';
    case PupList = 'Physically Unable to Perform';
    case Suspended = 'Suspended';
    case DayToDay = 'Day-To-Day';
}
