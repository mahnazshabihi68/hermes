<?php

namespace App\Services\Log\Impls\Drivers\File;

use App\Enums\Log\LogChannelEnum;
use App\Services\Log\Impls\Drivers\LogProcessor;
use Monolog\Logger;

class LogToFile
{
    /**
     * Create a custom Monolog instance.
     *
     * @param array $config
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger(LogChannelEnum::FILE_ASYNC->value);
        $logger->pushHandler(new LogFileHandler());
        $logger->pushProcessor(new LogProcessor());

        return $logger;
    }
}
