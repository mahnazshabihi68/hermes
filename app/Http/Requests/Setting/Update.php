<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{

    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [];
    }
}
