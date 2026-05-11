<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Exception;

class EspnUrlPatternResolverMismatchException extends \RuntimeException
{
    const EXCEPTION_TYPE_PATTERN = 'pattern';
    const EXCEPTION_TYPE_ATTRIBUTE = 'attribute';

    const EXCEPTION_MESSAGE = '%s is not a valid %s for ESPN URL pattern matching.';

    public function __construct(string $type, string $value)
    {
        $message = sprintf(
            self::EXCEPTION_MESSAGE,
            $value,
            $type
        );

        parent::__construct($message, $code, $previous);
    }
}
