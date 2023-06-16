<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Market.
 */

Route::prefix('markets')->namespace('Market')->name('markets.')->group(function () {
    Route::get(null, 'MarketController@getMarkets')->name('get-markets');
    Route::get('{market}', 'MarketController@getMarket')->name('get-market');
    Route::post(null, 'MarketController@storeMarket')->name('store-market');
    Route::match(['PUT', 'PATCH'], '{market}', 'MarketController@updateMarket')->name('update-market');
    Route::delete('{market}', 'MarketController@destroyMarket')->name('destroy-market');
    Route::get('last-day-data/{market}', 'MarketController@getMarketLastDayData')->name('get-market-last-day-data');
    Route::get('last-trades/{market}', 'MarketController@getMarketLastTrades')->name('get-market-last-trades');
    Route::get('candlestick-data/{market}/{timeframe}', 'MarketController@getMarketCandlestickData')->name('get-market-candlestick-data');
    Route::get('orderbook/{market}/{virtualOnly}', 'MarketController@getMarketOrderbook')->name('get-market-orderbook');
    Route::post('query', 'MarketController@queryMarkets')->name('markets-query');
});

/**
 * Order.
 */

Route::prefix('orders')->namespace('Order')->name('orders.')->group(function () {
    Route::get(null, 'OrderController@getOrders')->name('get-orders');
    Route::get('{order}', 'OrderController@getOrder')->name('get-order');
    Route::post(null, 'OrderController@storeOrder')->name('store-order');
    Route::delete('{order}', 'OrderController@cancelOrder')->name('cancel-order');
});

/**
 * Accountancy.
 */

Route::prefix('accountancy')->namespace('Accountancy')->name('accountancy.')->group(function () {
    Route::get(null, 'AccountancyController@list')->name('list');
});

/**
 * Settings.
 */

Route::prefix('settings')->name('settings.')->namespace('Setting')->group(function () {
    Route::get(null, 'SettingController@list')->name('list');
});

