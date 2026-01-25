<?php

declare(strict_types=1);

namespace AchyutN\NCM\Managers;

use AchyutN\NCM\Data\Order\CreateOrderRequest;
use AchyutN\NCM\Data\Order\Order;
use AchyutN\NCM\Exceptions\NCMException;

/** @phpstan-import-type OrderData from Order */
trait OrderManager
{
    /**
     * Create a new order.
     *
     * @throws NCMException
     */
    public function createOrder(CreateOrderRequest $createOrderRequest): Order
    {
        /** @var OrderData $response */
        $response = $this->client->post('/v1/order/create', $createOrderRequest->toArray());

        return new Order($response, $this);
    }

    /**
     * Get order details by order ID.
     *
     * @throws NCMException
     */
    public function getOrder(int $id): Order
    {
        /** @var OrderData $response */
        $response = $this->client->get('/v1/order', [
            'id' => $id,
        ]);

        return new Order($response, $this);
    }
}
