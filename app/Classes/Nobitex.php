<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Classes;

use App\Contracts\InteractsWithExchange;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Nobitex implements InteractsWithExchange
{
    const mainnetRestApiBase = 'https://api.nobitex.ir/';

    const testnetRestApiBase = 'https://testnetapi.nobitex.ir/';

    protected string $restApiBase;

    public function __construct()
    {
//        $this->setup();

        $this->restApiBase = self::mainnetRestApiBase;
    }

    /**
     * @param string $market
     * @return Collection
     * @throws GuzzleException
     */

    public function getOrderbook(string $market): Collection
    {
        $market = $this->getCastedMarket($market);

        return collect(json_decode($this->client()->get('v2/orderbook/' . $market)->getBody()->getContents()))->only(['bids', 'asks'])->map(function ($item, $list) use ($market) {
            return collect($item)->map(function ($item) use ($market, $list) {
                return collect([
                    'original_quantity' => (string)$item[1],
                    'original_price' => (string)(str_ends_with($market, 'IRT') ? $item[0] / 10 : $item[0]),
                    'type' => 'LIMIT',
                    'side' => $list == 'asks' ? 'BUY' : 'SELL',
                    'remained_quantity' => (string)$item[1]
                ]);
            });
        })->keyBy(fn($value, $key) => ($key == 'bids' ? 'asks' : 'bids'));
    }

    /**
     * @param string $market
     * @return string
     */

    protected function getCastedMarket(string $market): string
    {
        return str_contains($market, '-') ? str_replace('-', '', $market) : $market;
    }

    /**
     * @return Client
     */

    private function client(): Client
    {
        return new Client([
            'base_uri' => $this->restApiBase,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'http_errors' => false
        ]);
    }

    /**
     * @param string $market
     * @return Collection
     * @throws GuzzleException
     */

    public function getLastDayData(string $market): Collection
    {
        $symbolTicker = collect(json_decode($this->client()->post('market/stats', [
            'json' => [
                'srcCurrency' => substr($this->getCastedMarket($market), 0, -3),
                'dstCurrency' => 'IRT' // Todo: Not best practice here but we have to use nobitex only in IRT Dst markets.
            ],
        ])->getBody()->getContents())->stats)->values()->transform(function ($ticker) {
            return [
                'day_highest_price' => $ticker->dayHigh,
                'day_lowest_price' => $ticker->dayLow,
                'day_price_change' => bcsub($ticker->dayClose, $ticker->dayOpen),
                'day_price_change_percent' => $ticker->dayChange,
                'best_bid' => $ticker->bestSell,
                'best_ask' => $ticker->bestBuy,
                'last_price' => $ticker->latest,
                'src_volume' => $ticker->volumeSrc,
                'dst_volume' => $ticker->volumeDst
            ];
        })->first();

        return collect($symbolTicker);
    }

    /**
     * @return Collection
     * @throws GuzzleException
     * @throws Exception
     */

    public function getMarkets(): Collection
    {
        return collect(json_decode($this->client()->get('v2/orderbook/all')->getBody()->getContents()))
            ->except('status')
            ->map(fn($i) => collect($i)->only('asks')->values()->first())
            ->reject(fn($i) => !isset($i[0][0]))
            ->map(fn($i) => $i[0][0])
            ->map(function ($value, $key) {
                if (str_contains($key, 'IRT')) {
                    $value = (string)($value / 10);
                }
                return $value;
            });
    }

    public function getOrders(): Collection
    {
        // TODO: Implement getOrders() method.
    }

    public function getOrder(string $orderId, string|null $market): Collection
    {
        // TODO: Implement getOrder() method.
    }

    public function createOrder(string $market, float $original_quantity, string $type, string $side, float|null $original_price, float|null $original_stop_price): Collection
    {

    }

    public function cancelOrder(string $orderId): Collection
    {
        // TODO: Implement cancelOrder() method.
    }

    /**
     * @param string $market
     * @param string $from
     * @param string $to
     * @return Collection
     * @throws GuzzleException
     */

    public function getCandles(string $market, string $from, string $to): Collection
    {
        $data = collect(json_decode($this->client()->get('market/udf/history', [
            'query' => [
                'symbol' => $this->getCastedMarket($market),
                'resolution' => 'D',
                'from' => $from,
                'to' => $to
            ],
        ])->getBody()->getContents()))->reverse();

        $output = [];

        foreach ($data['t'] as $key => $timestamp) {

            $output[] = [
                'timestamp_casted_latin' => Carbon::parse($timestamp)->toFormattedDateString(),
                'timestamp_casted_jalali' => verta($timestamp)->format('%B %d، %Y'),
                'open_timestamp' => (string)$timestamp,
                'close_timestamp' => (string)$data['t'][$key],
                'open_price' => (string)$data['o'][$key],
                'high_price' => (string)$data['h'][$key],
                'low_price' => (string)$data['l'][$key],
                'close_price' => (string)$data['c'][$key],
                'volume' => (string)$data['v'][$key],
            ];

        }

        return collect($output);
    }

    /**
     * @param string $market
     * @return Collection
     * @throws GuzzleException
     */

    public function getLastTrades(string $market): Collection
    {
        $market = $this->getCastedMarket($market);

        return collect(json_decode($this->client()->get('v2/trades/' . $market)->getBody()->getContents())->trades)->transform(function ($trade) {
            return [
                'original_quantity' => $trade->volume,
                'original_price' => $trade->price,
                'side' => $trade->type === 'buy' ? 'BUY' : 'SELL',
                'created_at' => Carbon::parse($trade->time)
            ];
        });
    }

    /**
     * @return void
     */

    private function setup(): void
    {
        if (app()->environment('production')) {

            $this->restApiBase = self::mainnetRestApiBase;

            return;
        }

        $this->restApiBase = self::testnetRestApiBase;
    }
}
