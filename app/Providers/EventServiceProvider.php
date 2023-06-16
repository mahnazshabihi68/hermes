<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Providers;

use App\Events\Log\FileLogIsNeeded;
use App\Events\Log\MongoDBLogIsNeeded;
use App\Events\Log\MySQLLogIsNeeded;
use App\Listeners\Log\LogToFile;
use App\Listeners\Log\LogToMongoDB;
use App\Listeners\Log\LogToMySQL;
use App\Models\Order;
use App\Models\Trade;
use App\Observers\OrderObserver;
use App\Observers\TradeObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var \string[][]
     */
    protected $listen = [
        MySQLLogIsNeeded::class => [
            LogToMySQL::class,
        ],
        MongoDBLogIsNeeded::class => [
            LogToMongoDB::class,
        ],
        FileLogIsNeeded::class => [
            LogToFile::class
        ],
    ];
    /**
     * @var \string[][]
     */
    protected $observers = [
        Order::class => [OrderObserver::class],
        Trade::class => [TradeObserver::class]
    ];

    public function boot()
    {
        //
    }
}
