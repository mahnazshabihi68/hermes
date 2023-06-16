<?php
/*
 * This project is not free and has business trademarks which belongs to Vorna Company.
 *
 * Team-lead of software engineers contact information:
 * Ali Khedmati | +989122958172 | Ali@khedmati.ir
 * Copyright (c)  2020-2022, Vorna Co.
 */

return [
    'forbidden' => 'Oops! Your IP is not authorized.',
    'orders' => [
        'store' => [
            'successful' => 'Order had been successfully created.',
        ],
        'cancel' => [
            'successful' => 'Cancel order request had been successfully createted.',
            'isNotCancelable' => 'Requested order is not cancelable.',
        ],
    ],
    'markets' => [
        'enable-broadcast' => [
            'successful' => 'Request of broadcasting data was successful.',
        ],
        'store' => [
            'successful' => 'New market has been stored successfully.',
            'notUnique' => 'Market already exists.'
        ],
        'update' => [
            'successful' => 'Market has been updated successfully.'
        ],
        'destroy' => [
            'successful' => 'Market has been destroyed successfully.',
            'failed' => 'Market could not get destroyed.'
        ],
    ],
    'exceptions' => [
        'primary' => [
            \App\Exceptions\Primary\NotFoundException::MARKET_NOT_FOUND => 'Market not found!',
            \App\Exceptions\Primary\NotFoundException::ORDER_NOT_FOUND => 'Order not found!',
            \App\Exceptions\Primary\NotFoundException::TARGET_ORDER_NOT_FOUND => 'Target order not found!',
        ],
    ],
];
