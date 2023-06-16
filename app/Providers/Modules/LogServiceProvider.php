<?php

namespace App\Providers\Modules;




use App\Repositories\Log\Impls\LogMongoDBRepository;
use App\Repositories\Log\Impls\LogRepository;
use App\Repositories\Log\Interfaces\ILogMongoDBRepository;
use App\Repositories\Log\Interfaces\ILogRepository;
use App\Services\Log\Impls\LogService;
use App\Services\Log\Interfaces\ILogService;
use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    /**
     * All the container singletons that should be registered.
     *
     * @var array
     */
    public array $singletons = [
        ILogService::class => LogService::class,
        ILogRepository::class => LogRepository::class,
        ILogMongoDBRepository::class => LogMongoDBRepository::class,
    ];
}
