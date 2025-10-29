<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\Exception;

use RuntimeException;

class ImportConfigurationException extends RuntimeException {
    public function __construct(string $message = "Invalid configuration for import.")
    {
        parent::__construct($message);
    }
}
