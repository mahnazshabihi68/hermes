<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class NumberFormatCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return number_format($value, 8, '.', '');
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
