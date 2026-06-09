<?php

declare(strict_types=1);

namespace HansPeterOrding\EspnApiSymfonyBundle\MessageHandler\EspnSync;

use Psr\Log\LoggerInterface;

interface ImportMessageHandlerInterface
{
    public function setImportLogger(LoggerInterface $importLogger): void;
}
