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
use Illuminate\Support\Facades\DB;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Relations\HasMany;

class Order extends Model
{

    /**
     * @var string
     */

    protected $primaryKey = 'internal_order_id';

    /**
     * @var string[]
     */

    protected $fillable = [
        'internal_order_id',
        'exchange_order_id',
        'type',
        'side',
        'original_market_price',
        'original_price',
        'stop_price',
        'executed_price',
        'original_quantity',
        'executed_quantity',
        'cumulative_quote_quantity',
        'fill_percentage',
        'status',
        'is_virtual',
        'is_active_stop_limit'
    ];

    /**
     * @var string[]
     */

    protected $casts = [
        'internal_order_id' => 'string',
        'exchange_order_id' => 'string',
        'type' => 'string',
        'side' => 'string',
        'original_market_price' => 'decimal:8',
        'original_price' => 'decimal:8',
        'stop_price' => 'decimal:8',
        'executed_price' => 'decimal:8',
        'original_quantity' => 'decimal:8',
        'executed_quantity' => 'decimal:8',
        'cumulative_quote_quantity' => 'decimal:8',
        'fill_percentage' => 'decimal:2',
        'status' => 'string',
        'is_virtual' => 'boolean',
        'is_active_stop_limit' => 'boolean'
    ];

    /**
     * @var array
     */

    protected $attributes = [
        'exchange_order_id' => null,
        'status' => 'PENDING',
        'is_active_stop_limit' => false,
        'stop_price' => 0,
        'original_market_price' => 0,
        'executed_quantity' => 0
    ];

    /**
     * @var string[]
     */

    protected $appends = [
        'remained_quantity'
    ];

    /**
     * @param $value
     * @return void
     */

    public function setIsActiveStopLimitAttribute($value): void
    {
        $this->attributes['is_active_stop_limit'] = (boolean)$value;
    }


    /**
     * @param $value
     * @return void
     */

    public function setIsVirtualAttribute($value): void
    {
        $this->attributes['is_virtual'] = (boolean)$value;
    }

    /**
     * @return string
     */

    public function getRemainedQuantityAttribute(): string
    {
        return number_format(
            $this->attributes['original_quantity'] - $this->attributes['executed_quantity'],
            8,
            '.',
            ''
        );
    }

    /**
     * @return string
     */

    public function getRouteKeyName(): string
    {
        return 'internal_order_id';
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsOpen($query): mixed
    {
        return $query->where('fill_percentage', '<', 100)->where('status', '!=', 'CANCELED');
    }

    /**
     * @return bool
     */

    public function isCancelable(): bool
    {
        return self::isOpen()->get()->contains($this->attributes['internal_order_id']);
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsFilled($query): mixed
    {
        return $query->where('fill_percentage', 100);
    }

    /**
     * @param $query
     * @param  string  $type
     * @return mixed
     */

    public function scopeType($query, string $type): mixed
    {
        return $query->whereType($type);
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsVirtual($query): mixed
    {
        return $query->where('is_virtual', true);
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsReal($query): mixed
    {
        return $query->where('is_virtual', false);
    }

    /**
     * @return void
     * @throws \JsonException
     * @throws NotFoundException
     */

    public function createDirectOrder(): void
    {
        //todo: validations.

        /**
         * Fetch market.
         */

        $market = self::market()->first();
        if(!$market instanceof Market){
            throw new NotFoundException(NotFoundException::MARKET_NOT_FOUND);
        }

        DB::beginTransaction();
        try{
            /**
             * create order.
             */

            $createdOrder = $market->getExchange()
                ->createOrder(
                    $market->market,
                    $this->attributes['original_quantity'],
                    $this->attributes['type'],
                    $this->attributes['side'],
                    $this->attributes['original_price'],
                    $this->attributes['stop_price']
                );

            /**
             * Update order.
             */

            self::update([
                'status' => 'NEW',
                'exchange_order_id' => $createdOrder->first()['exchange_order_id'],
            ]);
            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollBack();
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            throw $exception;
        }

    }

    /**
     * @return BelongsTo
     */

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class, 'market_market');
    }

    /**
     * @return void
     * @throws \JsonException
     * @throws NotFoundException
     */

    public function updateDirectOrder(): void
    {
        //todo: validations.

        /**
         * Fetch market.
         */

        $market = self::market()->first();
        if(!$market instanceof Market){
            throw new NotFoundException(NotFoundException::MARKET_NOT_FOUND);
        }
        /**
         * Fetch the latest update of order.
         */

        $updatedOrder = $market->getExchange()->getOrder($this->attributes['exchange_order_id'], $market->market);

        DB::beginTransaction();
        try{
            /**
             * Submit trades.
             */

            foreach ($updatedOrder['trades'] as $trade) {
                $tradeExistence = self::trades()->where('exchange_trade_id', $trade['exchange_trade_id']);

                if ($tradeExistence->exists()) {
                    continue;
                }

                self::trades()->create($trade->toArray());
            }
            DB::commit();
        }
        catch(\Exception $exception){
            DB::rollBack();
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            throw $exception;
        }
    }

    /**
     * @return HasMany
     */

    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class, 'order_internal_order_id');
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsOTC($query): mixed
    {
        return $query->where('type', 'OTC');
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsPeerToPeer($query): mixed
    {
        return $query->whereIn('type', ['LIMIT', 'MARKET', 'STOPLOSSLIMIT']);
    }
}
