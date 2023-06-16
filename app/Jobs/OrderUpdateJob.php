<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Jobs;

use App\Models\Market;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderUpdateJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Market $market;

    protected bool $is_virtual;

    public function __construct(Market $market, bool $is_virtual)
    {
        $this->market = $market;

        $this->is_virtual = $is_virtual;

        $this->onQueue('orders-update-queue');
    }

    /**
     * @throws GuzzleException
     */

    public function handle()
    {
        try {

            /**
             * StopLossLimits
             */

            $this->market->handleStopLossLimitOrders();

            /**
             * Handle P2P Orders.
             */

            $this->market->matchOpenOrders($this->is_virtual);

            /**
             * Handle OTC orders.
             */

            $this->market->handleOTCOrders();

            /**
             * Direct orders.
             */

            $this->market->handleDirectOrders();

        } catch (\Exception $exception) {

            Log::critical($exception);

        }

    }

    /**
     * @return string
     */

    public function uniqueId(): string
    {
        return $this->market->market . ':' . ($this->is_virtual ? 'testnet' : 'mainnet') . ':' . 'orders:update:' . now()->unix();
    }

    /**
     * @return string[]
     */

    public function tags(): array
    {
        return [$this->market->market . ':' . ($this->is_virtual ? 'testnet' : 'mainnet')];
    }
}
