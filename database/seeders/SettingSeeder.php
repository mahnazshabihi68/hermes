<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $settings = [
            'binance-api-key' => '8loJJTuc3T4sYEaQtHQ7JAejWk3foQkKPTmkyTJMYpQ54Jr3ic8kJFIP3qLq99Ba',
            'binance-secret-key' => 'D3OLNrZ6hDUmLhFs3O0yD0cdUAC8Ao40xWy2Mzn2OVME5lJezcULKSu5sT1GzSBb',
            'nobitex-api-key' => '',
            'nobitex-secret-key' => '',
        ];

        foreach ($settings as $key => $value) {

            Setting::create([
                'key' => $key,
                'value' => $value
            ]);

        }
    }
}
