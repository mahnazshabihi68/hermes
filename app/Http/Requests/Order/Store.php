<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:LIMIT,MARKET,STOPLOSSLIMIT,OTC',
            'side' => 'required|string|in:BUY,SELL',
            'market' => 'required|string',
            'original_quantity' => 'required|numeric|gt:0.000001',
            'original_price' => 'nullable|numeric|required_unless:type,MARKET,OTC|gt:0.000001',
            'stop_price' => 'nullable|numeric|required_if:type,STOPLOSSLIMIT',
            'is_virtual' => 'required|boolean'
        ];
    }

    public function bodyParameters(): array
    {

        return [
            'type' => [
                'description' => 'The execution type of order.',
                'example' => 'LIMIT'
            ],
            'side' => [
                'description' => 'The side of order.',
                'example' => 'SELL'
            ],
            'market' => [
                'description' => 'The name of market.',
                'example' => 'BTC-USDT'
            ],
            'original_quantity' => [
                'description' => 'The quantity of order.',
                'example' => '0.12345'
            ],
            'original_price' => [
                'description' => 'The price of order.',
                'example' => '20000'
            ],
            'stop_price' => [
                'description' => 'The trigger price for stoplosslimit type of orders.',
                'example' => '25000'
            ],
            'is_virtual' => [
                'description' => 'The queue of order.',
                'example' => '0',
            ],
        ];

    }
}
