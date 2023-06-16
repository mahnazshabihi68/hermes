<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Console\Commands;

use App\Jobs\OrderUpdateJob;
use App\Models\Order;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OrderUpdateCommand extends Command
{
    protected $signature = 'orders:update';

    protected $description = 'Updating orders.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {

            $this->info($this->signature . ' had been started successfully at ' . now());

            /**
             * Check if we have any open order to deal with or not.
             */

            $openOrders = Order::whereHas('market', fn($market) => $market->isActive())->isOpen();

            if ($openOrders->count() === 0) {

                return;

            }

            /**
             * Dispatch the OrderUpdateJob Queue.
             */

            $openOrders->get()->unique(fn($order) => $order->market->market . $order->is_virtual)->flatten()->each(function ($order) {

                OrderUpdateJob::dispatch($order->market, $order->is_virtual);

                $this->info($order->market->market . ' ' . ($order->is_virtual ? 'testnet' : 'mainnet') . ' has been dispatched.');

            });

        } catch (Exception $exception) {

            Log::critical($exception);

            $this->error($this->signature . ' had been faced serious problem at ' . now());

        }
    }
}
