<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Providers;

use App\Enums\Log\LogChannelEnum;
use App\Helpers\Logger;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
//        Log::channel('stderr')->info('Start cache setting--------------------------');
//        $settings = cache()->rememberForever('settings', function () {
//
//            Log::channel('stderr')->info('Pluck key and value cache setting------------'. Setting::query()->pluck('value', 'key'). '-----------------------');
////            Logger::error('error', data: Setting::query()->pluck('value', 'key'), channel: LogChannelEnum::STDERR);
//            return Setting::query()->pluck('value', 'key');
//
//        });
//
//        /**
//         * Push settings into config() helper.
//         */
//
//        foreach ($settings as $key => $value) {
//
//            Log::channel('stderr')->info('Before config cache setting------------'.$key.'-----', $value);
//            config()->set('settings.' . $key, $value);
//            Log::channel('stderr')->info('After config cache setting------------'.$key.'-----', $value);
//
//        }
//
//        Log::channel('stderr')->info('End cache setting--------------------------');
    }
}
