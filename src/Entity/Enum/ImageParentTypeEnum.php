<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum ImageParentTypeEnum: string
{
    case Team = 'team';
    case Venue = 'venue';
}
