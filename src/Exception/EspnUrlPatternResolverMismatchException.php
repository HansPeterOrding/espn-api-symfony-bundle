<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Exception;

use RuntimeException;

class EspnUrlPatternResolverMismatchException extends RuntimeException
{
    public const string EXCEPTION_TYPE_PATTERN = 'pattern';
    public const string EXCEPTION_TYPE_ATTRIBUTE = 'attribute';
    private const string EXCEPTION_MESSAGE = '%s is not a valid %s for ESPN URL pattern matching.';

    public function __construct(string $type, string $value)
    {
        $message = sprintf(
            self::EXCEPTION_MESSAGE,
            $value,
            $type
        );

        parent::__construct($message);
    }
}
