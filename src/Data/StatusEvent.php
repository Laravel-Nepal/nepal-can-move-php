<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Data;

use Illuminate\Support\Carbon;
use LaravelNepal\NCM\Enums\EventStatus;
use LaravelNepal\NCM\Enums\OrderStatus as OrderStatusEnum;
use LaravelNepal\NCM\Exceptions\NCMException;

/**
 * @phpstan-type StatusEventData array{
 *     order_id?: string|int,
 *     order_ids?: array<string|int>,
 *     event: string,
 *     timestamp: string,
 *     status: string,
 * }
 *
 * @template-extends BaseData<StatusEventData>
 */
final class StatusEvent extends BaseData
{
    /** @var array<int> */
    public array $orderIds = [];

    public EventStatus $event;

    public Carbon $timestamp;

    public string $status;

    public function getOrderStatus(): OrderStatusEnum
    {
        return $this->event->toOrderStatus();
    }

    /**
     * Check if the webhook contains multiple orders.
     */
    public function isBulk(): bool
    {
        return count($this->orderIds) > 1;
    }

    /**
     * @throws NCMException
     */
    protected function fromResponse(array $response): void
    {
        if (isset($response['order_ids']) && is_array($response['order_ids'])) {
            $this->orderIds = array_map(intval(...), $response['order_ids']);
        } elseif (isset($response['order_id'])) {
            $this->orderIds = [(int) $response['order_id']];
        }

        if ($this->orderIds === []) {
            throw new NCMException('Webhook payload must contain at least one order ID.');
        }

        $this->status = $response['status'];
        $this->event = EventStatus::tryFrom($response['event']) ?? throw new NCMException("Unknown event type: {$response['event']}");
        $this->timestamp = Carbon::parse($response['timestamp']);
    }
}
