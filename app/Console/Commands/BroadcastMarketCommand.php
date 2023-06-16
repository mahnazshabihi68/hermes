<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Console\Commands;

use App\Jobs\BroadcastMarketJob;
use App\Models\Market;
use Illuminate\Console\Command;

class BroadcastMarketCommand extends Command
{
    protected $signature = 'markets:broadcast';

    protected $description = 'Dispatches the BroadcastMarketJob.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
//        if (!env('SHOULD_BROADCAST_MARKET', false)) {
//
//            return;
//
//        }

        while (true) {

            try {

                /**
                 * Dispatch the job.
                 */

                Market::isActive()->get()->each(function ($market) {

                    foreach ([true, false] as $queue) {

                        BroadcastMarketJob::dispatch($market, $queue);

                        /**
                         * Sleep.
                         */

                        usleep(100000);

                    }

                });

            } catch (\Exception $exception) {

                $this->error($exception->getMessage());

                break;
            }

        }
    }
}
