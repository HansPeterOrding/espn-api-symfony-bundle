<?php

declare (strict_types = 1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Entity\Enum;

enum EspnCompetitionStatusTypeEnum: string
{
    case STATUS_SCHEDULED = 'STATUS_SCHEDULED';
    case STATUS_IN_PROGRESS = 'STATUS_IN_PROGRESS';
    case STATUS_FINAL = 'STATUS_FINAL';

    public function getState(): string
    {
        return match ($this) {
            self::STATUS_SCHEDULED => 'pre',
            self::STATUS_IN_PROGRESS => 'in',
            self::STATUS_FINAL => 'post',
        };
    }

    public function isCompleted(): bool
    {
        return match ($this) {
            self::STATUS_FINAL => true,
            default => false
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::STATUS_SCHEDULED => 'Scheduled',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_FINAL => 'Final',
        };
    }
}
