<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum EspnCompetitionStatusTypeStateEnum: string
{
    case PRE = 'pre';
    case POST = 'post';
}
