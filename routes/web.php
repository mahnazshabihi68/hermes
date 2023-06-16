<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Download Postman API Collection of app.
 */

Route::get('postman', 'Postman\PostmanController@download')->name('download-postman-collection');

/**
 * Api-docs
 */

Route::get('api-docs', fn() => File::get(public_path('docs/index.html')))->name('api-docs');

/**
 * Landing Page.
 */

Route::get(null, function () {
    return view('welcome');
});

Route::get('/test', function () {
    event (new \App\Jobs\PingJob(['a' => 'hiiiii']));
//    return view('welcome');
});
