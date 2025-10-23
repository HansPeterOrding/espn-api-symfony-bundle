<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum EspnBroadcastMarketType: string
{
    case NATIONAL = 'national';
    case INTERNATIONAL = 'international';
}
