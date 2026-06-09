<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Psr\Log\LoggerInterface;

trait ImportMessageHandlerTrait
{
    private LoggerInterface $importLogger;

    public function setImportLogger(LoggerInterface $importLogger): void
    {
        $this->importLogger = $importLogger;
    }
}
