<?php

namespace App\Services\Log\Impls\Drivers\MongoDB;

use App\Enums\Log\LogChannelEnum;
use App\Services\Log\Impls\Drivers\LogProcessor;
use Monolog\Logger;

class LogToMongoDB
{
    /**
     * Create a custom Monolog instance.
     *
     * @param array $config
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger(LogChannelEnum::MONGODB->value);
        $logger->pushHandler(new LogMongoDBHandler());
        $logger->pushProcessor(new LogProcessor());

        return $logger;
    }
}
