<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum NoteParentTypeEnum: string
{
    case Athlete = 'athlete';
    case Team = 'team';
}
