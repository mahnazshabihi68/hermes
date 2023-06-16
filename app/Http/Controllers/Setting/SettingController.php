<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Http\Controllers\Setting;

use App\Helpers\Logger;
use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * @group Settings
 *
 * APIs for handling settings of application.
 */
class SettingController extends Controller
{

    /**
     * @return JsonResponse
     */

    /**
     * Get All Settings
     *
     * This endpoint will deliver all settings that have been stored in database.
     * @throws \JsonException
     */

    public function list(): JsonResponse
    {
        try {
            return response()->json([
                'settings' => Setting::latest()->get()
            ]);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * Todo: Update function.
     */
}
