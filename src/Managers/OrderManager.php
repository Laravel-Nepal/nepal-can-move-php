<?php

declare(strict_types=1);

namespace AchyutN\NCM\Managers;

use AchyutN\NCM\Data\Order\CreateOrderRequest;
use AchyutN\NCM\Data\Order\Order;
use AchyutN\NCM\Data\Order\OrderStatus;
use AchyutN\NCM\Exceptions\NCMException;
use Illuminate\Support\Collection;

/**
 * @phpstan-import-type OrderData from Order
 * @phpstan-import-type OrderStatusData from OrderStatus
 */
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

    /**
     * Get order details by order ID.
     *
     * @return Collection<int, OrderStatus>
     *
     * @throws NCMException
     */
    public function getOrderStatus(int $id): Collection
    {
        /** @var array<OrderStatusData> $response */
        $response = $this->client->get('/v1/order/status', [
            'id' => $id,
        ]);

        return collect($response)->map(fn ($status): OrderStatus => new OrderStatus($status, $this));
    }

    /**
     * Add comment to an order.
     */
    public function addOrderComment(int $id, string $comment): bool
    {
        try {
            $this->client->post('/v1/comment', [
                'orderid' => $id,
                'comments' => $comment,
            ]);
        } catch (NCMException) {
            return false;
        }

        return true;
    }
}
