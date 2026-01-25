<?php

declare(strict_types=1);

namespace AchyutN\NCM\Managers;

use AchyutN\NCM\Data\Order\CreateOrderRequest;
use AchyutN\NCM\Data\Order\Order;
use AchyutN\NCM\Exceptions\NCMException;

trait OrderManager
{
    /**
     * Create a new order.
     *
     * @throws NCMException
     */
    public function createOrder(CreateOrderRequest $createOrderRequest): Order
    {
        $response = $this->client->post('/v1/order/create', $createOrderRequest->toArray());

        return new Order($response, $this);
    }
}
