<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Console\Commands;

use App\Models\Market;
use App\Traits\ExternalExchange;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class MarketUpdateCommand extends Command
{
    use ExternalExchange;

    protected $signature = 'markets:update';

    protected $description = 'Updates the markets.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {

            while (true) {

                foreach ($this->getAllMarkets() as $market => $data) {

                    Market::firstOrCreate([
                        'market' => $market,
                        'exchange' => $data['exchange']
                    ])->update([
                        'exchange_price' => $data['price']
                    ]);

                }

                $this->info($this->signature . ' has launched successfully at: ' . now());

                sleep(10);

            }


        } catch (Exception|GuzzleException $exception) {

            $this->error($this->signature . ' failed! Message: ' . $exception->getMessage());

        }
    }
}
