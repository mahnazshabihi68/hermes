<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{
    /**
     * @param Order $order
     * @return void
     */

    public function creating(Order $order): void
    {
        $order->internal_order_id = Str::orderedUuid()->toString();
    }

    /**
     * @param Order $order
     * @return void
     */

    public function saving(Order $order): void
    {
        /**
         * Update fill percentage.
         */

        $order->fill_percentage = $order->executed_quantity / $order->original_quantity * 100;

        /**
         * Update Status.
         */

        match (true) {
            $order->fill_percentage == 100 => $order->status = 'FILLED',
            ($order->fill_percentage > 0 && $order->fill_percentage < 100) && $order->status != 'CANCELED' => $order->status = 'PARTIALLY_FILLED',
            default => $order->status
        };

        /**
         * Calculate cumulative price and quote.
         */

        $executedPrice = 0;

        $trades = $order->trades()->get()->map(function ($trade) use ($order) {
            $trade->weight = $trade->quantity / $order->original_quantity;
            return $trade;
        });

        $sumWeights = $trades->sum('weight');

        foreach ($trades as $trade) {

            $executedPrice += $trade->weight * $trade->price / $sumWeights;

        }

        $order->executed_price = $executedPrice;

        $order->cumulative_quote_quantity = $order->executed_quantity * $executedPrice;
    }
}
