<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace Database\Seeders;

use App\Models\Market;
use App\Traits\ExternalExchange;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Seeder;

class MarketSeeder extends Seeder
{
    use ExternalExchange;

    /**
     * @throws GuzzleException
     */

    public function run()
    {

        foreach ($this->getAllMarkets() as $market => $data) {

            if (!in_array($market, $this->getActiveMarkets())) {

                continue;

            }

            Market::create([
                'market' => $market,
                'exchange_price' => $data['price'],
                'exchange' => $data['exchange'],
                'is_active' => in_array($market, $this->getActiveMarkets())
            ]);

        }
    }

    /**
     * @return array
     */

    public function getActiveMarkets(): array
    {
        return ['BTC-IRT', 'BTC-USDT', 'ETH-USDT', 'ETH-IRT'];
    }
}
