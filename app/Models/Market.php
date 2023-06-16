<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Models;

use App\Exceptions\Primary\NotFoundException;
use App\Helpers\Logger;
use App\Helpers\Util;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Relations\HasMany;

class Market extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string
     */

    protected $primaryKey = 'market';

    /**
     * @var string[]
     */

    protected $fillable = [
        'market',
        'is_active',
        'is_internal',
        'is_direct',
        'exchange',
        'exchange_price'
    ];

    /**
     * @var string[]
     */

    protected $casts = [
        'market' => 'string',
        'is_active' => 'boolean',
        'is_internal' => 'boolean',
        'is_direct' => 'boolean',
        'exchange' => 'string',
        'exchange_price' => 'decimal:8'
    ];

    protected $attributes = [
        'is_active' => false,
        'is_direct' => false,
        'is_internal' => false
    ];

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsActive($query): mixed
    {
        return $query->whereIsActive(true);
    }

    /**
     * @return Collection
     */

    public function getSymbols(): Collection
    {
        $exploded = explode('-', $this->attributes['market']);

        return collect([
            'src' => $exploded[0],
            'dst' => $exploded[1]
        ]);
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsInternal($query): mixed
    {
        return $query->whereIsInternal(true);
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsDirect($query): mixed
    {
        return $query->whereIsDirect(true);
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsNotDirect($query): mixed
    {
        return $query->whereIsDirect(false);
    }

    /**
     * @param $query
     * @param  string  $exchange
     * @return mixed
     */

    public function scopeExchange($query, string $exchange): mixed
    {
        return $query->whereExchange($exchange);
    }

    /**
     * @param $query
     * @param  string  $market
     * @return mixed
     */

    public function scopeMarket($query, string $market): mixed
    {
        return $query->whereMarket($market)->orWhereRaw([
            '$where' => "this.market.replace(/[ -]/g,'') == '$market'"
        ]);
    }

    /**
     * @return void
     */

    public function handleOTCOrders(): void
    {
        self::orders()
            ->isOpen()
            ->isOTC()
            ->chunkById(5, function ($orders) {
                foreach ($orders as $order) {
                    $order->trades()->create([
                        'quantity' => $order->original_quantity,
                        'price' => $this->attributes['exchange_price'],
                        'causes_inequality' => !$order['is_virtual'],
                        'is_cleared' => false
                    ]);
                }
            });
    }

    /**
     * @return HasMany
     */

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'market_market');
    }

    /**
     * @param  bool  $virtualOnly
     * @return void
     * @throws GuzzleException
     * @throws \JsonException
     * @throws NotFoundException
     */

    public function matchOpenOrders(bool $virtualOnly): void
    {
        /**
         * get orderbook.
         */

        $orderbook = $this->getOrderbook($virtualOnly)->map(function ($order, $k) {
            $order['rq'] = $order['remained_quantity'];
            $order['match_key'] = $k;
            return $order;
        });

        /**
         * Process orderbook.
         */

        while ($orderbook->isNotEmpty()) {
            $order = $orderbook->whereNotNull('internal_order_id');

            if ($order->isEmpty()) {
                break;
            }

            $order = $orderbook->pull($order->first()['match_key']);

            $orq = $order['rq'];

            while ($orq > 0 && $orderbook->isNotEmpty()) {
                $targetOrders = $orderbook->skipUntil(function ($targetOrder) use ($order) {
                    $passesQuantity = $targetOrder['rq'] > 0;

                    // Side Check.

                    $passesSide = $targetOrder['side'] != $order['side'];

                    // Price check.

                    $passesPrice = $targetOrder['original_price'] != 0 || $order['original_price'] != 0;

                    if ($order['original_price'] > 0 && $targetOrder['original_price'] > 0) {
                        $priceComparison = bccomp($order['original_price'], $targetOrder['original_price'], 8);

                        $passesPrice = match ($order['side']) {
                            'BUY' => $priceComparison != -1,
                            'SELL' => $priceComparison != 1
                        };
                    }

                    // Return.

                    return $passesQuantity && $passesSide && $passesPrice;
                });

                if ($targetOrders->isEmpty()) {
                    break;
                }

                $targetOrders = match ($order['side']) {
                    'BUY' => $targetOrders->sortBy('original_price'),
                    'SELL' => $targetOrders->sortByDesc('original_price')
                };

                $targetOrder = $orderbook->pull($targetOrders->first()['match_key']);

                $bcSub = bcsub($orq, $targetOrder['rq'], 8);

                $tradeQuantity = match (true) {
                    $bcSub <= 0 => $orq,
                    $bcSub > 0 => $targetOrder['rq']
                };

                $tradePrice = $targetOrder['original_price'] > 0 ? $targetOrder['original_price'] : $order['original_price'];

                $orq -= $tradeQuantity;

                $targetOrder['rq'] -= $tradeQuantity;

                $order = Order::query()->find($order['internal_order_id']);
                if(!$order instanceof Order){
                    throw new NotFoundException(NotFoundException::ORDER_NOT_FOUND);
                }

                DB::beginTransaction();
                try {
                    $order->trades()->create([
                        'quantity' => $tradeQuantity,
                        'price' => $tradePrice,
                        'causes_inequality' => !$order['is_virtual'] && !isset($targetOrder['internal_order_id']),
                        'is_cleared' => isset($targetOrder['internal_order_id'])
                    ]);

                    /**
                     * Fill Target order if it has to be filled.
                     */

                    if (isset($targetOrder['internal_order_id'])) {
                        $targetOrder = Order::query()->find($targetOrder['internal_order_id']);
                        if(!$targetOrder instanceof Order){
                            throw new NotFoundException(NotFoundException::TARGET_ORDER_NOT_FOUND);
                        }

                        $targetOrder->trades()->create([
                            'quantity' => $tradeQuantity,
                            'price' => $tradePrice,
                            'causes_inequality' => !$order['is_virtual'] && !isset($order['internal_order_id']),
                            'is_cleared' => isset($order['internal_order_id'])
                        ]);
                    }
                    DB::commit();
                } catch (Exception $exception) {
                    DB::rollback();
                    Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
                    throw $exception;
                }
            }
        }
    }

    /**
     * @param  bool  $virtualOnly
     * @param  bool  $groupByList
     * @return Collection
     * @throws GuzzleException
     * @throws \JsonException
     */

    public function getOrderbook(bool $virtualOnly, bool $groupByList = false): Collection
    {
        /**
         * Fetch local orders.
         */

        $openOrders = $this->getOpenOrders($virtualOnly);

        $openOrders->isEmpty() ? $orderbooks = collect([]) : $orderbooks = $openOrders->reject(function ($order) {
            return ($order['type'] == 'STOPLOSSLIMIT' && (bool)($order['is_active_stop_limit']) == false);
        });

        if ((!$this->attributes['is_internal'] || $virtualOnly) && class_exists($this->attributes['exchange'])) {
            try {
                $orderbooks = $this->getExternalOrderbook()->flatten(1)->values()->merge($orderbooks->toArray());
            } catch (GuzzleException|Exception $exception) {
//                Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
//                throw $exception;
            }
        }

        /**
         * Return.
         */

        $orderbooks = $this->sortOrderbook($orderbooks);

        if ($groupByList) {
            $orderbooks = $orderbooks->groupBy(fn($item) => $item['side'] == 'BUY' ? 'bids' : 'asks');
        }

        return $orderbooks;
    }

    /**
     * @param  bool  $virtualOnly
     * @return Collection
     */

    public function getOpenOrders(bool $virtualOnly): Collection
    {
        return self::orders()
            ->isOpen()
            ->isPeerToPeer()
            ->whereHas('market', fn($market) => $market->isNotDirect())
            ->{$virtualOnly ? 'isVirtual' : 'isReal'}()
            ->get();
    }

    /**
     * @return Collection
     * @throws GuzzleException
     * @throws \JsonException
     */

    public function getExternalOrderbook(): Collection
    {
        try {
            $orderbook = collect(
                json_decode(
                    Redis::get(
                        'external-orderbook:'.strtolower(str_replace('-', '', $this->attributes['market']))
                    ) ?? throw new Exception
                    ,
                    true
                )
            );
        } catch (Exception $exception) {
//            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            $orderbook = $this->getExchange()->getOrderbook($this->attributes['market']);
        }

        return $orderbook;
    }

    /**
     * @return mixed
     */

    public function getExchange(): mixed
    {
        return new $this->attributes['exchange']();
    }

    /**
     * @param  Collection  $orderbook
     * @return Collection
     */

    public function sortOrderbook(Collection $orderbook): Collection
    {
        return $orderbook->sortBy([
            ['type', 'desc'],
            ['original_price', 'desc'],
            ['created_at', 'desc']
        ]);
    }

    /**
     * @return Collection
     * @throws GuzzleException
     * @throws \JsonException
     */

    public function getLastDayData(): Collection
    {
        try {
            $lastDayData = collect(
                json_decode(
                    Redis::get(
                        'external-ticker:'.strtolower(str_replace('-', '', $this->attributes['market']))
                    ) ?? throw new Exception
                    ,
                    true
                )
            );
        } catch (Exception $exception) {
//            Logger::info($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            $lastDayData = $this->getExchange()->getLastDayData($this->attributes['market']);
        }

        return $lastDayData;
    }

    /**
     * @return Collection
     * @throws Exception
     */

    public function getLastTrades(): Collection
    {
        try {
            $trades = collect(
                json_decode(
                    Redis::get(
                        'external-trades:'.strtolower(str_replace('-', '', $this->attributes['market']))
                    ) ?? throw new Exception
                    ,
                    true
                )
            );
        } catch (Exception $exception) {
//            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            $trades = $this->getExchange()->getLastTrades($this->attributes['market']);
        }

        return $trades;
    }

    /**
     * @param  string  $from
     * @param  string  $to
     * @return Collection
     */

    public function getCandlestickData(string $from, string $to): Collection
    {
        return $this->getExchange()->getCandles($this->attributes['market'], $from, $to);
    }

    /**
     * @return void
     * @throws \JsonException
     */

    public function handleDirectOrders(): void
    {
        self::orders()
            ->isOpen()
            ->isPeerToPeer()
            ->whereHas('market', fn($market) => $market->isActive()->isDirect())
            ->isReal()
            ->chunkById(3, function ($orders) {
                foreach ($orders as $order) {
                    DB::beginTransaction();
                    try {
                        $order = Order::find($order['internal_order_id']);
                        $createDirectOrder = $order->createDirectOrder();
                        $updateDirectOrder = $order->updateDirectOrder();
                        DB::commit();
                        match ($order['status']) {
                            'PENDING' => $createDirectOrder,
                            'NEW', 'PARTIALLY_FILLED' => $updateDirectOrder
                            //todo:cancel.
                        };
                    } catch (Exception $exception) {
                        DB::rollback();
                        Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
                        continue;
                    }
                }
            });
    }

    /**
     * @return void
     */

    public function handleStopLossLimitOrders(): void
    {
        /**
         * Fetch all stopLossLimit Order.
         */

        self::orders()->isOpen()->type('STOPLOSSLIMIT')->where('is_active_stop_limit', false)->get()->each(
            function ($order) {
                /**
                 * Check if order can be converted to Limit or not.
                 */

                $currentMarketPrice = $order->market->exchange_price;

                $shouldActivate = ($order->stop_price > $order->original_market_price && $order->stop_price < $currentMarketPrice)
                    || ($order->stop_price < $order->original_market_price && $order->stop_price > $currentMarketPrice);

                if ($shouldActivate) {
                    $order->update([
                        'is_active_stop_limit' => true
                    ]);
                }
            }
        );
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeHasWebsocketStream($query): mixed
    {
        return $query->where('exchange', 'LIKE', '%'.'Binance'.'%');
    }
}
