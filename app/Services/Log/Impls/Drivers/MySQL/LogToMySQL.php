<?php

namespace App\Services\Log\Impls\Drivers\MySQL;

use App\Enums\Log\LogChannelEnum;
use App\Services\Log\Impls\Drivers\LogProcessor;
use Monolog\Logger;

class LogToMySQL
{
    /**
     * Create a custom Monolog instance.
     *
     * @param array $config
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger(LogChannelEnum::MYSQL->value);
        $logger->pushHandler(new LogMySQLHandler());
        $logger->pushProcessor(new LogProcessor());

        return $logger;
    }
}
