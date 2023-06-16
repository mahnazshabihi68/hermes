<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Http\Controllers\Market;

use App\Exceptions\Primary\NotFoundException;
use App\Helpers\Logger;
use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\Market\Query;
use App\Http\Requests\Market\Store;
use App\Http\Requests\Market\Update;
use App\Models\Market;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

/**
 * @group Markets
 *
 * APIs for managing markets.
 *
 * <aside>
 * <ul>
 * <li>Please provide the <b>market</b> attribute in all APIs related to in <b>Snake_Case</b>.<br>Examples:BTC-USDT, ETH-IRT and ...</li>
 * <li>You still can provide <b>market</b> attribute in <b>PascalCase</b> but beware that the <b>Accountancy</b> module would not work properly and as expected. </li>
 * <ul>
 * </aside>
 */
class MarketController extends Controller
{
    /**
     * @return JsonResponse
     */

    /**
     * Get All
     *
     * This endpoint will deliver an object of all markets sorted by descending order of @created_at.
     * @throws \JsonException
     */

    public function getMarkets(): JsonResponse
    {
        try {
            /**
             * Return response.
             */

            return response()->json([
                'markets' => Market::latest()->get()
            ]);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage(),
            ], 400);
        }
    }

    /**
     * @param  Market  $market
     * @return JsonResponse
     */

    /**
     * Get By Name
     *
     * This endpoint will deliver the very single object of specified market by it's name.
     * @urlParam market string required The name of the market. Example: BTC-USDT
     * @throws \JsonException
     */

    public function getMarket(string $market): JsonResponse
    {
        try {
            return response()->json([
                'market' => Market::market($market)->firstOrFail()
            ]);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @param  Query  $request
     * @return JsonResponse
     */

    /**
     * Query
     *
     * This endpoint will accept an array of markets and search database for proper results.
     * @throws \JsonException
     */

    public function queryMarkets(Query $request): JsonResponse
    {
        try {
            /**
             * Return response.
             */

            return response()->json([
                'markets' => Market::whereIn('market', $request->markets)->latest()->get()
            ]);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @param  Store  $request
     * @return JsonResponse
     */

    /**
     * Store
     *
     * This endpoint will store a new market.
     * @throws \JsonException
     */

    public function storeMarket(Store $request): JsonResponse
    {
        try {
            /**
             * Todo: Validate that the provided exchange has the provided market or not.
             */

            $market = $request->only([
                'market',
                'is_active',
                'is_direct',
                'is_internal',
                'exchange'
            ]);

            /**
             * Handle exchange.
             */

            $market['exchange'] = 'App\\Classes\\'.$request->exchange;

            /**
             * Create new market.
             */

            $market = Market::create($market);

            /**
             * Return response.
             */

            return response()->json([
                'message' => __('messages.markets.store.successful'),
                'market' => $market->fresh()
            ], 201);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @param  Market  $market
     * @param  Update  $request
     * @return JsonResponse
     */

    /**
     * Update
     *
     * This endpoint will update an existing market.
     * @throws NotFoundException
     */

    public function updateMarket(string $market, Update $request): JsonResponse
    {
        //        try {

        /**
         * Todo: Validate that the provided exchange has the provided market or not.
         */


        $data = $request->only([
            'market',
            'is_active',
            'is_direct',
            'is_internal',
            'exchange'
        ]);


        /**
         * Handle exchange.
         */

        $data['exchange'] = 'App\\Classes\\'.$request->exchange;


        /**
         * Update market.
         */

        $market = Market::market($market)->first();
        if (!$market instanceof Market) {
            throw new NotFoundException(NotFoundException::MARKET_NOT_FOUND);
        }

        $market->update($data);


        /**
         * Return response.
         */

        return response()->json([
            'message' => __('messages.markets.update.successful'),
            'market' => $market->fresh()
        ]);

//        } catch (\Throwable $exception) {
//
//            return response()->json([
//                'error' => $exception->getMessage()
//            ], 400);
//
//        }
    }

    /**
     * @param  Market  $market
     * @return JsonResponse
     */

    /**
     * Destroy
     *
     * This endpoint will delete market.
     * @urlParam market string required The name of the market. Example: BTC-USDT
     * @throws \JsonException
     */

    public function destroyMarket(string $market): JsonResponse
    {
        try {
            Market::market($market)->delete();

            return response()->json([
                'message' => __('messages.markets.destroy.successful')
            ]);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @param  Market  $market
     * @param  string  $timeframe
     * @return JsonResponse
     * @throws GuzzleException
     */

    /**
     * Get Candlesticks
     *
     * This endpoint will deliver the market's candlestick data on requested timeframe.
     * <aside class="notice">Available timeframes are: <b>1W, 1M, 1Y</b></aside>
     * @urlParam market string required The name of the market. Example: BTC-USDT
     * @urlParam timeframe string required The desired timeframe of candle. Example: 1Y
     *
     * @throws \JsonException
     */

    public function getMarketCandlestickData(string $market, string $timeframe): JsonResponse
    {
        try {
            Validator::make(['timeframe' => $timeframe], [
                'timeframe' => ['required', 'string', 'in:1W,1M,1Y']
            ])->validate();

            /**
             * Generate unix timestamps.
             */

            $from = match ($timeframe) {
                '1W' => now()->subWeek(),
                '1M' => now()->subMonth(),
                '1Y' => now()->subYear()
            };

            /**
             * Get candles.
             */

            $candlestickData = Market::market($market)->firstOrFail()?->getCandlestickData(
                $from->unix(),
                now()->subDay()->unix()
            );

            /**
             * Handle 1Y transform to 12M.
             */

            if ($timeframe === '1Y') {
                $candlestickData = $candlestickData->chunk(30)->map(fn($c) => $c->first());
            }

            /**
             * Return response.
             */

            return response()->json([
                'candlestick-data' => $candlestickData
            ]);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @param  Market  $market
     * @return JsonResponse
     * @throws GuzzleException
     */

    /**
     * Get 24H Ticker
     *
     * This endpoint will deliver the market's statistics (Ticker) of last 24 hour.
     * @urlParam market string required The name of the market. Example: BTC-USDT
     * @throws \JsonException
     */

    public function getMarketLastDayData(string $market): JsonResponse
    {
        try {
            /**
             * Return response.
             */

            return response()->json([
                'last-day-data' => Market::market($market)->firstOrFail()?->getLastDayData()
            ]);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @param  Market  $market
     * @param  bool  $virtualOnly
     * @return JsonResponse
     */

    /**
     * Get Last OrderBook
     *
     * This endpoint will deliver the most recent orderbook of market in descending order of time.
     *
     * @urlParam market string required The name of the market. Example: BTC-USDT
     * @urlParam virtualOnly integer Defines the queue of order, if it's true, only the virtual orders would be the outcome. Example: 0
     * @throws \JsonException
     */

    public function getMarketOrderbook(string $market, bool $virtualOnly): JsonResponse
    {
        try {
            return response()->json([
                'orderbook' => Market::market($market)->firstOrFail()?->getOrderbook($virtualOnly)
            ]);
        } catch (Exception|GuzzleException $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @param  Market  $market
     * @return JsonResponse
     */

    /**
     * Get Last Trades
     *
     * This endpoint will deliver the market's most recent trades in descending order of time.
     *
     * @urlParam market string required The name of the market. Example: BTC-USDT
     * @throws \JsonException
     */

    public function getMarketLastTrades(string $market): JsonResponse
    {
        try {
            return response()->json([
                'last-trades' => Market::market($market)->firstOrFail()?->getLastTrades()
            ]);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

}
