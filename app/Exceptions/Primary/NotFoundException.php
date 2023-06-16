<?php

namespace App\Exceptions\Primary;

use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends PrimaryBaseException
{
    /**
     * @var string
     */
    public const MARKET_NOT_FOUND = 'market_not_found';
    /**
     * @var string
     */
    public const ORDER_NOT_FOUND = 'order_not_found';
    /**
     * @var string
     */
    public const TARGET_ORDER_NOT_FOUND = 'target_order_not_found';

    /**
     * @param  string  $exceptionMessage
     * @param  int|null  $exceptionStatusCode
     */
    public function __construct(string $exceptionMessage, ?int $exceptionStatusCode = Response::HTTP_NOT_FOUND)
    {
        parent::__construct($exceptionMessage, $exceptionStatusCode);
    }
}
