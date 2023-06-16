<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Http\Controllers\Accountancy;

use App\Helpers\Logger;
use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Accountancy;
use Illuminate\Http\JsonResponse;

/**
 * @group Accountancy
 *
 * APIs for handling accountancy.
 */
class AccountancyController extends Controller
{
    /**
     * @return JsonResponse
     */

    /**
     * Get Inequalities
     * @throws \JsonException
     */

    public function list(): JsonResponse
    {
        try {

            return response()->json([
                'inequalities' => Accountancy::getInequalities()
            ]);

        } catch (\Exception $exception) {
            Logger::error($exception->getMessage(),Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);

        }
    }
}
