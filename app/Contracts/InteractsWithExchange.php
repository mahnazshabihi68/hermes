<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Contracts;

use Illuminate\Support\Collection;

interface InteractsWithExchange
{
    /**
     * @return Collection
     */

    public function getMarkets(): Collection;

    /**
     * @param string $market
     * @return Collection
     */

    public function getLastDayData(string $market): Collection;

    /**
     * @return Collection
     */

    public function getOrders(): Collection;

    /**
     * @param string $orderId
     * @param string|null $market
     * @return Collection
     */

    public function getOrder(string $orderId, string|null $market): Collection;

    /**
     * @param string $market
     * @param float $original_quantity
     * @param string $type
     * @param string $side
     * @param float|null $original_price
     * @param float|null $original_stop_price
     * @return Collection
     */

    public function createOrder(string $market, float $original_quantity, string $type, string $side, float|null $original_price, float|null $original_stop_price): Collection;

    /**
     * @param string $orderId
     * @return Collection
     */

    public function cancelOrder(string $orderId): Collection;

    /**
     * @param string $market
     * @return Collection
     */

    public function getOrderbook(string $market): Collection;

    /**
     * @param string $market
     * @param string $from
     * @param string $to
     * @return Collection
     */

    public function getCandles(string $market, string $from, string $to): Collection;

    public function getLastTrades(string $market): Collection;
}
