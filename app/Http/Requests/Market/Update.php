<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Http\Requests\Market;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
//        $except = $this->market;

        return [
//            'market' => 'string|unique:markets,market,' . $except,
            'is_active' => 'required|boolean',
            'is_internal' => 'required|boolean',
            'is_direct' => 'required|boolean',
            'exchange' => 'required|string|in:Binance,Nobitex',
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'market' => [
                'description' => 'The name of market.',
                'example' => 'QLC-BTC',
            ],
            'is_active' => [
                'description' => 'Determines if market is active or not, market deactivation means all broadcasts, order matching and ... are going to terminate.',
                'example' => '1',
            ],
            'is_internal' => [
                'description' => 'Determines if orderbook of market should include orderbooks from third-party exchange or not.',
                'example' => '0',
            ],
            'is_direct' => [
                'description' => 'Determines matching orders method, if its equals true, all orders will be matched by entered third-party exchange, otherwise all orders will get queued to match by internal match engine.',
                'example' => '0',
            ],
            'exchange' => [
                'description' => 'The third-party exchange which supports your market.',
                'example' => 'Binance',
            ],
        ];
    }
}
