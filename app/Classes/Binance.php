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

class Binance implements InteractsWithExchange
{
    const mainnetRestApiBase = 'https://api.binance.com/api/v3/';

    const websocketStreamBase = 'wss://stream.binance.com:9443/';

    const testnetRestApiBase = 'https://testnet.binance.vision/api/v3/';

    const testnetApiKey = 'tOHWNL53ZJvrUicFJ7OOtPq180lT3WTcfOKXdNlcF6W8mAbfNqrWFjX4IV9ruafO';

    const testnetSecretKey = 'X9XYh93vY6uX8K8jZdYtFP8ahv5gPu3IR05GC6B0Qbn7OkbiBSfV0Zmey7FwmjE9';

    /**
     * @var string
     */

    protected string $restApiBase;

    /**
     * @var string
     */

    protected string $websocketStreamBase;

    /**
     * @var string
     */

    protected string $apiKey;

    /**
     * @var string
     */

    protected string $secretKey;

    /**
     * Binance constructor.
     */

    public function __construct()
    {
//        $this->setup();

        $this->restApiBase = self::mainnetRestApiBase;

        $this->websocketStreamBase = self::websocketStreamBase;

        $this->apiKey = config('app.binance_api_key');
        $this->secretKey = config('app.binance_secret_key');
    }

    /**
     * @return Collection
     * @throws GuzzleException
     */

    public function getMarkets(): Collection
    {
        return collect(json_decode($this->client()->get('ticker/price')->getBody()->getContents()))?->pluck('price', 'symbol');
    }

    /**
     * @param bool $isAuthenticated
     * @return Client
     */

    public function client(bool $isAuthenticated = false): Client
    {
        $headers = [
            'accept' => 'application/json'
        ];

        if ($isAuthenticated) {

            $headers['X-MBX-APIKEY'] = $this->apiKey;

        }

        return new Client([
            'base_uri' => $this->restApiBase,
            'headers' => $headers,
            'http_errors' => false
        ]);
    }

    /**
     * @param string $market
     * @return Collection
     * @throws GuzzleException
     */

    public function getOrderbook(string $market): Collection
    {
        return collect(json_decode($this->client()->get('depth', [
            'query' => [
                'symbol' => $this->getCastedMarket($market),
                'limit' => 50
            ],
        ])->getBody()->getContents()))->only(['bids', 'asks'])->map(function ($item, $list) {
            return collect($item)->map(function ($item) use ($list) {
                return collect([
                    'original_quantity' => (string)$item[1],
                    'original_price' => (string)$item[0],
                    'type' => 'LIMIT',
                    'side' => $list == 'asks' ? 'SELL' : 'BUY',
                    'remained_quantity' => (string)$item[1],
                ]);
            });
        });
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
     * @param string $market
     * @param float $original_quantity
     * @param string $type
     * @param string $side
     * @param float|null $original_price
     * @param float|null $original_stop_price
     * @return Collection
     * @throws GuzzleException
     * @throws Exception
     */

    public function createOrder(string $market, float $original_quantity, string $type, string $side, null|float $original_price, null|float $original_stop_price): Collection
    {
        if ($type === 'STOPLOSSLIMIT') {

            $type = 'STOP_LOSS_LIMIT';

        }

        $body = [
            'timestamp' => now()->timestamp * 1000,
            'type' => $type,
            'quantity' => $original_quantity,
            'symbol' => $this->getCastedMarket($market),
            'side' => $side,
            'newOrderRespType' => 'FULL'
        ];

        if (in_array($type, ['LIMIT', 'STOP_LOSS_LIMIT', 'TAKE_PROFIT_LIMIT'])) {

            $body['timeInForce'] = 'GTC';

        }

        if ($original_price) {

            $body['price'] = $original_price;

        }

        if ($original_stop_price) {

            $body['stopPrice'] = $original_stop_price;

        }

        $body['signature'] = $this->hashHmacGenerator($body);

        $request = $this->client(true)->post('order', [
            'form_params' => $body,
        ]);

        if ($request->getStatusCode() != 200) {

            throw new Exception(json_decode($request->getBody()->getContents())->msg);

        }

        return collect(json_decode($request->getBody()->getContents())->orderId)->transform(fn($orderId) => ['exchange_order_id' => $orderId]);
    }

    /**
     * @param array $data
     * @return string
     */

    private function hashHmacGenerator(array $data): string
    {
        return hash_hmac('sha256', http_build_query($data), $this->secretKey);
    }

    /**
     * @param string $orderId
     * @param string $symbol
     * @return Collection
     * @throws GuzzleException
     */

    public function cancelOrder(string $orderId): Collection
    {
        $body = [
            'timestamp' => now()->timestamp * 1000,
            'origClientOrderId' => $orderId,
//            'symbol' => str_contains($symbol, '-') ? str_replace('-', null, $symbol) : $symbol
        ];

        $body['signature'] = $this->hashHmacGenerator($body);

        $request = $this->client(true)->delete('order', [
            'query' => $body,
        ]);

        return collect(json_decode($request->getBody()->getContents()));
    }

    /**
     * @param string $market
     * @return Collection
     * @throws GuzzleException
     */

    public function getLastDayData(string $market): Collection
    {
        $ticker = collect(json_decode($this->client()->get('ticker/24hr', [
            'query' => [
                'symbol' => $this->getCastedMarket($market)
            ],
        ])->getBody()->getContents()));

        return collect([
            'day_highest_price' => $ticker['highPrice'],
            'day_lowest_price' => $ticker['lowPrice'],
            'day_price_change' => $ticker['priceChange'],
            'day_price_change_percent' => $ticker['priceChangePercent'],
            'best_bid' => $ticker['bidPrice'],
            'best_ask' => $ticker['askPrice'],
            'last_price' => $ticker['lastPrice'],
            'src_volume' => $ticker['volume'],
            'dst_volume' => $ticker['quoteVolume']
        ]);
    }

    public function getOrders(): Collection
    {
        // TODO: Implement getOrders() method.
    }

    /**
     * @param string $orderId
     * @param string|null $market
     * @return Collection
     * @throws GuzzleException
     * @throws Exception
     */

    public function getOrder(string $orderId, string|null $market): Collection
    {
        $body = [
            'timestamp' => now()->timestamp * 1000,
            'orderId' => $orderId,
            'symbol' => $this->getCastedMarket($market)
        ];

        $body['signature'] = $this->hashHmacGenerator($body);

        $request = $this->client(true)->get('order', [
            'query' => $body,
        ]);

        if ($request->getStatusCode() != 200) {

            throw new Exception(json_decode($request->getBody()->getContents())->msg);

        }

        $request = collect(json_decode($request->getBody()->getContents()));

        return collect([
            'orderId' => $request['orderId'],
            'market' => $request['symbol'],
            'side' => $request['side'],
            'type' => $request['type'],
            'original_quantity' => $request['origQty'],
            'executed_quantity' => $request['executedQty'],
            'cumulative_quote_quantity' => $request['cummulativeQuoteQty'],
            'original_price' => $request['price'],
            'executed_price' => $request['cummulativeQuoteQty'] / $request['executedQty'],
            'stop_price' => $request['stopPrice'],
            'status' => $request['status']
        ])->map(fn($value) => (string)$value)->put('trades', $this->getTradesOfOrder($orderId, $market));
    }

    /**
     * @param string $orderId
     * @param string $market
     * @return Collection
     * @throws GuzzleException
     * @throws Exception
     */

    public function getTradesOfOrder(string $orderId, string $market): Collection
    {
        $body = [
            'timestamp' => now()->timestamp * 1000,
            'orderId' => $orderId,
            'symbol' => $this->getCastedMarket($market)
        ];

        $body['signature'] = $this->hashHmacGenerator($body);

        $request = $this->client(true)->get($this->restApiBase . 'myTrades', [
            'query' => $body,
        ]);

        if ($request->getStatusCode() != 200) {

            throw new Exception(json_decode($request->getBody()->getContents())->msg);

        }

        return collect(json_decode($request->getBody()->getContents()))->map(function ($trade) {

            return collect([
                'exchange_trade_id' => $trade->id,
                'price' => $trade->price,
                'quantity' => $trade->qty,
            ])->map(fn($v) => (string)$v);

        });
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
        return collect(json_decode($this->client()->get('klines', [
            'query' => [
                'symbol' => $this->getCastedMarket($market),
                'interval' => '1d',
                'startTime' => $from * 1000,
                'endTime' => $to * 1000
            ],
        ])->getBody()->getContents()))->transform(function ($value) {
            return collect([
                'timestamp_casted_latin' => Carbon::createFromTimestamp($value[0] / 1000)->toFormattedDateString(),
                'timestamp_casted_jalali' => verta($value[0] / 1000)->format('%B %d، %Y'),
                'open_timestamp' => (string)(Carbon::createFromTimestamp($value[0] / 1000)->unix()),
                'close_timestamp' => (string)(Carbon::createFromTimestamp($value[6] / 1000)->unix()),
                'open_price' => (string)$value[1],
                'high_price' => (string)$value[2],
                'low_price' => (string)$value[3],
                'close_price' => (string)$value[4],
                'volume' => (string)$value[5]
            ]);
        });
    }

    /**
     * @param string $market
     * @return Collection
     * @throws GuzzleException
     */

    public function getLastTrades(string $market): Collection
    {
        return collect(json_decode($this->client()->get('trades', [
            'query' => [
                'symbol' => $this->getCastedMarket($market)
            ]
        ])->getBody()->getContents()))->transform(function ($trade) {
            return [
                'original_quantity' => $trade->qty,
                'original_price' => $trade->price,
                'side' => $trade->isBuyerMaker ? 'BUY' : 'SELL',
                'created_at' => Carbon::parse($trade->time / 1000)
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

            $this->apiKey = config('settings.binance-api-key');

            $this->secretKey = config('settings.binance-secret-key');

            return;
        }

        $this->restApiBase = self::testnetRestApiBase;

        $this->apiKey = self::testnetApiKey;

        $this->secretKey = self::testnetSecretKey;
    }
}
