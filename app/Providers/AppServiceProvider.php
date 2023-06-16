<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        /**
         * Broadcasting websocket config
         */
        if (App::environment('production') || App::environment('stage')) {
            \URL::forceScheme('https');
        }

//        $pusherScheme = Str::startsWith(env('APP_URL'), 'https') ? 'https' : 'http';

//        config()->set('broadcasting.connections.pusher.options.host', Str::after(config('app.url'), '://'));

//        config()->set('broadcasting.connections.pusher.options.scheme', $pusherScheme);

//        $pusherScheme == 'https' ? config()->set('broadcasting.connections.pusher.options.useTLS', true) : config()->set('broadcasting.connections.pusher.options.useTLS', false);

        cache()->rememberForever('git', function () {
            return collect([
                'branch' => shell_exec('git rev-parse --abbrev-ref HEAD'),
                'tag' => exec('git tag') ?? '1.0.0',
                'latest_commit_hash' => shell_exec('git log --pretty=format:"%h" -n 1'),
                'latest_commit_time' => shell_exec('git log -1 --format=%ci')
            ]);
        });
    }
}
