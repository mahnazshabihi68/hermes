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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;

class Trade extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */

    protected $fillable = [
        'exchange_trade_id',
        'causes_inequality',
        'is_cleared',
        'quantity',
        'price',
        'internal_trade_id'
    ];

    protected $appends = ['cumulative_quote_quantity'];

    protected $casts = [
        'quantity' => 'decimal:8',
        'price' => 'decimal:8',
        'cumulative_quote_quantity' => 'decimal:8',
        'causes_inequality' => 'boolean',
        'is_cleared' => 'boolean'
    ];

    /**
     * @param $value
     * @return void
     */

    public function setCausesInequalityAttribute($value)
    {
        $this->attributes['causes_inequality'] = (boolean)$value;
    }

    /**
     * @param $value
     * @return void
     */

    public function setIsClearedAttribute($value)
    {
        $this->attributes['is_cleared'] = (boolean)$value;
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeCausesInequality($query): mixed
    {
        return $query->where('causes_inequality', true);
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsCleared($query): mixed
    {
        return $query->whereIsCleared(true);
    }

    /**
     * @param $query
     * @return mixed
     */

    public function scopeIsNotCleared($query): mixed
    {
        return $query->whereIsCleared(false);
    }

    /**
     * @return mixed
     * @throws NotFoundException
     */
    public function market(): mixed
    {
        $order = self::order()->first();
        if(!$order instanceof Order){
            throw new NotFoundException(NotFoundException::ORDER_NOT_FOUND);
        }
        return $order->market()->first();
    }

    /**
     * @return BelongsTo
     */

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_internal_order_id');
    }

    /**
     * @return string
     */

    protected function getCumulativeQuoteQuantityAttribute(): string
    {
        return $this->castAttribute(
            'cumulative_quote_quantity',
            $this->attributes['price'] * $this->attributes['quantity']
        );
    }
}
