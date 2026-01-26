<?php

declare(strict_types=1);

namespace AchyutN\NCM\Managers;

use AchyutN\NCM\Data\Order\Comment;
use AchyutN\NCM\Data\Order\CreateOrderRequest;
use AchyutN\NCM\Data\Order\Order;
use AchyutN\NCM\Data\Order\OrderStatus;
use AchyutN\NCM\Data\Order\RedirectOrderRequest;
use AchyutN\NCM\Exceptions\NCMException;
use Illuminate\Support\Collection;

/**
 * @phpstan-import-type OrderData from Order
 * @phpstan-import-type OrderStatusData from OrderStatus
 * @phpstan-import-type CommentData from Comment
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
     * Get order status of multiple orders.
     *
     * @param  array<int>  $ids
     * @return Collection<int, OrderStatus>
     *
     * @throws NCMException
     */
    public function getOrdersStatuses(array $ids): Collection
    {
        /** @var array{'result': array<int, string>, 'errors': array<int>} $response */
        $response = $this->client->post('/v1/orders/statuses', [
            'orders' => $ids,
        ]);

        return collect($response['result'])
            ->map(fn (string $status, int $orderId): OrderStatus => new OrderStatus([
                'orderid' => $orderId,
                'status' => $status,
            ], $this))
            ->values();
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

    /**
     * Fetches comments of an order.
     *
     * @return Collection<int, Comment>
     *
     * @throws NCMException
     */
    public function getOrderComments(int $id): Collection
    {
        /** @var array<CommentData> $response */
        $response = $this->client->get('/v1/order/comment', [
            'id' => $id,
        ]);

        return collect($response)->map(fn (array $comment): Comment => new Comment($comment, $this));
    }

    /**
     * Fetches comments done by NCM for multiple orders.
     * This will return last 25 comments.
     *
     * @return Collection<int, Comment>
     *
     * @throws NCMException
     */
    public function getOrdersComments(): Collection
    {
        /** @var array<CommentData> $response */
        $response = $this->client->get('/v1/order/getbulkcomments');

        if (isset($response['detail'])) {
            return collect();
        }

        return collect($response)
            ->values()
            ->map(fn (array $comment): Comment => new Comment($comment, $this));
    }

    /**
     * Mark an order for return process.
     *
     * @param  string|null  $reason  Reason for returning the order
     *
     * @throws NCMException
     */
    public function returnOrder(int $id, ?string $reason = null): bool
    {
        $this->client->post('/v2/vendor/order/return', [
            'pk' => $id,
            'comment' => $reason,
        ]);

        return true;
    }

    /**
     * Mark an order for exchange process.
     *
     * @throws NCMException
     */
    public function exchangeOrder(int $id): true
    {
        $this->client->post('/v2/vendor/order/exchange-create', [
            'pk' => $id,
        ]);

        return true;
    }

    /**
     * Mark an order for redirect to another customer.
     *
     * @throws NCMException
     */
    public function redirectOrder(RedirectOrderRequest $redirectOrderRequest): bool
    {
        $this->client->post('/v2/vendor/order/redirect', $redirectOrderRequest->toArray());

        return true;
    }
}
