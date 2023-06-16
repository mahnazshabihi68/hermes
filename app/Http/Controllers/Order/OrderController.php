<?php

/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

namespace App\Http\Controllers\Order;

use App\Helpers\Logger;
use App\Helpers\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\Store;
use App\Models\Market;
use App\Models\Order;
use Exception;
use Illuminate\Http\JsonResponse;

use function __;
use function response;

/**
 * @group Orders
 *
 * APIs for managing orders.
 *
 * <aside>
 * <ul>
 * <li>All order id's in url parameters should be <b>internal_order_id</b> property of order.</li>
 * <ul>
 * </aside>
 */
class OrderController extends Controller
{

    /**
     * @return JsonResponse
     */

    /**
     * Get All
     *
     * This endpoint will deliver an object of all orders sorted by descending order of @created_at.
     * @throws \JsonException
     */

    public function getOrders(): JsonResponse
    {
        try {
            return response()->json([
                'orders' => Order::without(['trades', 'market'])->latest()->get()
            ]);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @param  Store  $request
     * @return JsonResponse
     */

    /**
     * Store
     *
     * This endpoint will store a new order.
     * @throws \JsonException
     */

    public function storeOrder(Store $request): JsonResponse
    {
        try {
            /**
             * Find market.
             */

            $market = Market::isActive()->market($request->market)->firstOrFail();

            $data = $request->all();

            $data['original_market_price'] = $market->exchange_price;

            if (in_array($request->type, ['MARKET', 'OTC'])) {
                $data['original_price'] = 0;
            }

            /**
             * Create new order.
             */

            $order = $market->orders()->create($data);

            /**
             * Return response.
             */

            return response()->json([
                'message' => __('messages.orders.store.successful'),
                'order' => $order->fresh()
            ], 201);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage(),
            ], 400);
        }
    }

    /**
     * @param  Order  $order
     * @return JsonResponse
     */

    /**
     * Get By ID
     *
     * This endpoint will deliver the very single object of specified order by it's ID.
     * @urlParam order string required The <b>internal_order_id</b> of order. Example: 968310e5-7681-418b-b203-4ebeccbba0e2
     * @throws \JsonException
     */

    public function getOrder(Order $order): JsonResponse
    {
        try {
            return response()->json([
                'order' => Order::with('trades')->findOrFail($order->internal_order_id)
            ]);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * @param  Order  $order
     * @return JsonResponse
     */

    /**
     * Cancel
     *
     * This endpoint will cancel the order if it's cancelable.
     * @urlParam order string required The <b>internal_order_id</b> of order. Example: 9682ff55-f205-4af4-8d1b-189b2dbb5ff2
     * @throws \JsonException
     */

    public function cancelOrder(Order $order): JsonResponse
    {
        try {
            /**
             * Check if order is cancelable or not.
             */

            if (!$order->isCancelable()) {
                return response()->json([
                    'error' => __('messages.orders.cancel.isNotCancelable')
                ]);
            }

            /**
             * Cancel order if it has been issued on direct exchange.
             */

            if ($order->market()->first()->is_direct) {
                // Todo

            }

            /**
             * Update order.
             */

            $order->update([
                'status' => 'CANCELED',
            ]);

            /**
             * Return response.
             */

            return response()->json([
                'message' => __('messages.orders.cancel.successful'),
                'order' => $order->fresh(),
            ]);
        } catch (Exception $exception) {
            Logger::error($exception->getMessage(), Util::jsonEncodeUnicode($exception->getTrace()));
            return response()->json([
                'error' => $exception->getMessage(),
            ], 400);
        }
    }
}
