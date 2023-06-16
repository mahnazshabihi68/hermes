<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Traits;

use App\Classes\Binance;
use App\Classes\Nobitex;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;

trait ExternalExchange
{
    /**
     * @return Collection
     */

    public function getAllMarkets(): Collection
    {
        $acceptedDstSymbols = collect(['USDT', 'IRT', 'BTC', 'ETH']);

        $markets = collect();

        try {

            $markets = $markets->merge($this->nobitex()->getMarkets()->transform(fn($price, $market) => [$market => ['price' => $price, 'exchange' => get_class($this->nobitex())]]));

        } catch (Exception|GuzzleException) {
        }

        try {

            $markets = $markets->merge($this->binance()->getMarkets()->transform(fn($price, $market) => [$market => ['price' => $price, 'exchange' => get_class($this->binance())]]));

        } catch (Exception|GuzzleException) {
        }

        $markets = $markets->map(fn($data, $market) => collect($data)->unique($market));

        $output = [];

        foreach ($markets as $market => $data) {

            foreach ($acceptedDstSymbols as $symbol) {

                if (str_ends_with($market, $symbol)) {

                    $srcSymbol = substr($market, 0, (strlen($market) - strlen($symbol)));

                    $output[$srcSymbol . '-' . $symbol] = $data->values()[0];
                }


            }

        }

        return collect($output);
    }

    /**
     * @return Nobitex
     */

    #[Pure] public function nobitex(): Nobitex
    {
        return new Nobitex();
    }

    /**
     * @return Binance
     */

    #[Pure] public function binance(): Binance
    {
        return new Binance();
    }
}
