<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Entity\Enum;

enum EspnBroadcastMarketType: string
{
    case NATIONAL = 'national';
    case INTERNATIONAL = 'international';
}
