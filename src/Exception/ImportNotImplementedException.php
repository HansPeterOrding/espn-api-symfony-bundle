<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Exception;

use RuntimeException;

class ImportNotImplementedException extends RuntimeException
{
    private const string MESSAGE = "%s import is not implemented yet.";

    public function __construct(string $importType)
    {
        parent::__construct(
            sprintf(self::MESSAGE, $importType)
        );
    }
}
