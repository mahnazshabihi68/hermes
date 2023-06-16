<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Jobs;

use App\Events\Markets\BroadcastMarketEvent;
use App\Models\Market;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BroadcastMarketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Market $market;

    protected bool $is_virtual;

    public function __construct(Market $market, bool $is_virtual)
    {
        $this->market = $market;

        $this->is_virtual = $is_virtual;

        $this->onQueue('broadcast-market-queue');
    }

    public function handle()
    {
        try {
            Log::channel('stderr')->info('start broadcast market queue');
            BroadcastMarketEvent::dispatch($this->market, $this->is_virtual);
            Log::channel('stderr')->info('end broadcast market queue');

        } catch (\Throwable $x) {
            Log::channel('stderr')->info('exception: ' . $x->getMessage());
        }
    }

    /**
     * @return string[]
     */

    public function tags(): array
    {
        return [$this->market->market . ':' . ($this->is_virtual ? 'testnet' : 'mainnet') . ':broadcast-market'];
    }
}
