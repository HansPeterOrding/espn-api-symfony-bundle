<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum EspnTeamRecordItemTypeEnum: string
{
    case TOTAL = 'total';
    case HOME = 'home';
    case ROAD = 'road';
}
