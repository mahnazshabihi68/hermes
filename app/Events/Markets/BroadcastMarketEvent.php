<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Events\Markets;

use App\Models\Market;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BroadcastMarketEvent implements ShouldBroadcastNow, ShouldBeUnique
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Market $market;

    public bool $is_virtual;

    public array $orderbook;

    public array $lastDayData;

    public array $trades;

    /**
     * @throws GuzzleException
     */

    public function __construct(Market $market, bool $is_virtual)
    {
        $this->market = $market;

        $this->is_virtual = $is_virtual;

        $this->orderbook = $this->market->getOrderbook($this->is_virtual, true)->toArray();

        $this->lastDayData = $this->market->getLastDayData()->toArray();

        $this->trades = $this->market->getLastTrades()->toArray();
    }

    /**
     * @return Channel
     */

    public function broadcastOn(): Channel
    {
        Log::channel('stderr')->info('Before Broadcast ' . new Channel($this->market->market . '.' . ($this->is_virtual ? 'testnet' : 'mainnet')));
        return new Channel($this->market->market . '.' . ($this->is_virtual ? 'testnet' : 'mainnet'));
    }

    /**
     * @return string
     */

    public function broadcastAs(): string
    {
        return 'marketUpdated';
    }
}
