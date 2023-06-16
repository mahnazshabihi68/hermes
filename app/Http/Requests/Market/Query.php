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

class Query extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'markets' => 'required|array',
            'markets.*' => 'required|string'
        ];
    }

    public function bodyParameters(): array
    {
        return [
            'markets' => [
                'description' => 'Markets names.',
                'example' => ["BTC-USDT", "ETH-USDT"]
            ],
        ];
    }
}
