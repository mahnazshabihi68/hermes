<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Accountancy extends Model
{
    public static function getInequalities(bool $isCleared = false): Collection
    {
        $output = [];

        $trades = Trade::causesInequality()->{$isCleared ? 'isCleared' : 'isNotCleared'}()->latest()->get();

        foreach ($trades as $trade) {

            $marketSymbols = $trade->market()->getSymbols();

            $srcInequality = 0;

            $dstInequality = 0;

            match ($trade->order()->first()->side) {
                'BUY' => [
                    'src' => $srcInequality -= $trade['quantity'],
                    'dst' => $dstInequality += $trade['cumulative_quote_quantity'],
                ],
                'SELL' => [
                    'src' => $srcInequality += $trade['quantity'],
                    'dst' => $dstInequality -= $trade['cumulative_quote_quantity']
                ],
            };

            isset($output[$marketSymbols['src']]) ? $output[$marketSymbols['src']] += $srcInequality : $output[$marketSymbols['src']] = $srcInequality;

            isset($output[$marketSymbols['dst']]) ? $output[$marketSymbols['dst']] += $dstInequality : $output[$marketSymbols['dst']] = $dstInequality;

        }

        return collect($output)->map(fn($value, $key) => number_format($value, 8, '.', ''));
    }
}
