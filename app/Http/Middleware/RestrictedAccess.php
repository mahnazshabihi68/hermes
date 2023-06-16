<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RestrictedAccess
{
    protected Collection $allowedIps;

    public function __construct()
    {
        $this->allowedIps = strlen(config('app.allowed-ips')) > 0 && str_contains(config('app.allowed-ips'), ',') ? collect(implode(',', config('app.allowed-ips'))) : collect();
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->allowedIps->count() > 0) {

            if ($this->allowedIps->doesntContain($request->ip())) {

                return response()->json([
                    'error' => __('messages.forbidden')
                ], 403);

            }

        }

        return $next($request);
    }
}
