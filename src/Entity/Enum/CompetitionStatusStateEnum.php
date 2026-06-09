<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum CompetitionStatusStateEnum: string
{
    case Pre = 'pre';
    case In = 'in';
    case Post = 'post';
}
