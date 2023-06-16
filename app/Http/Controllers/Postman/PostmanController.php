<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Http\Controllers\Postman;

use App\Helpers\Logger;
use App\Helpers\Util;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use function collect;
use function response;
use function storage_path;

class PostmanController extends Controller
{

    /**
     * @throws \JsonException
     */
    public function download()
    {

        try {

            /**
             * Fetch postman collection.
             */

            $collection = collect(Storage::disk('local')->files('postman'))->last();

            /**
             * Return download response.
             */

            return response()->download(storage_path('app/' . $collection));

        } catch (\Exception $exception) {
            Logger::error($exception->getMessage(),Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);

        }
    }
}
