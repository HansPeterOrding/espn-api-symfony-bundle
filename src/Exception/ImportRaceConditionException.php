<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Exception;

use RuntimeException;

class ImportRaceConditionException extends RuntimeException
{
    private const string RACE_CONDITION_MESSAGE = 'Race condition detected: Import %s first.';

    public function __construct(string $missingObjectDescription)
    {
        parent::__construct(sprintf(
            self::RACE_CONDITION_MESSAGE,
            $missingObjectDescription
        ));
    }
}
