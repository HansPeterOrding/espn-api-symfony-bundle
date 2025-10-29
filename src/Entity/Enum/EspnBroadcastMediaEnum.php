<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum EspnBroadcastMediaEnum: string {
    case ABC = 'ABC';
    case CBS = 'CBS';
    case ESPN = 'ESPN';
    case FOX = 'FOX';
    case NBC = 'NBC';
    case NETFLIX = 'Netflix';
    case NFL_NET = 'NFL Net';
    case NFL_PLUS = 'NFL+';
    case PARAMOUNT_PLUS = 'Paramount+';
    case PEACOCK = 'Peacock';
    case PRIME_VIDEO = 'Prime Video';
    case YOUTUBE = 'YouTube';
}
