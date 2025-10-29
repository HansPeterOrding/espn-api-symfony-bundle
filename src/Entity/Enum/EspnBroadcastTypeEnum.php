<?php

declare (strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum EspnBroadcastTypeEnum: string
{
    case TV = 'TV';
    case STREAMING = 'Streaming';
}
