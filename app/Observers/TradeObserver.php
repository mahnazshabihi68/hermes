<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Observers;

use App\Exceptions\Primary\NotFoundException;
use App\Models\Order;
use App\Models\Trade;
use Illuminate\Support\Str;

class TradeObserver
{
    /**
     * @param Trade $trade
     * @return void
     */

    public function creating(Trade $trade): void
    {
        $trade->internal_trade_id = Str::orderedUuid()->toString();
    }

    /**
     * @param  Trade  $trade
     * @return void
     * @throws NotFoundException
     */

    public function created(Trade $trade): void
    {
        /**
         * Add to executed quantity of order.
         */

        $order = $trade->order()->first();
        if(!$order instanceof Order){
            throw new NotFoundException(NotFoundException::ORDER_NOT_FOUND);
        }

        $order->executed_quantity += $trade->quantity;

        $order->save();
    }

}
