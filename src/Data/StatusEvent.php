<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Data;

use Illuminate\Support\Carbon;
use LaravelNepal\NCM\Enums\EventStatus;
use LaravelNepal\NCM\Enums\OrderStatus as OrderStatusEnum;
use LaravelNepal\NCM\Exceptions\NCMException;

/**
 * @phpstan-type StatusEventData array{
 *     order_id: string,
 *     event: string,
 *     timestamp: string,
 *     status: string,
 * }
 *
 * @template-extends BaseData<StatusEventData>
 */
final class StatusEvent extends BaseData
{
    public int $orderId;

    public EventStatus $event;

    public Carbon $timestamp;

    public string $status;

    public function getOrderStatus(): OrderStatusEnum
    {
        return $this->event->toOrderStatus();
    }

    /**
     * @throws NCMException
     */
    protected function fromResponse(array $response): void
    {
        $this->orderId = (int) $response['order_id'];
        $this->status = $response['status'];
        $this->event = EventStatus::tryFrom($response['event']) ?? throw new NCMException("Unknown event type: {$response['event']}");
        $this->timestamp = Carbon::parse($response['timestamp']);
    }
}
