<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Data;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use LaravelNepal\NCM\Enums\EventStatus;

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

    protected function fromResponse(array $response): void
    {
        $this->orderId = (int) $response['order_id'];
        $this->status = $response['status'];
        $this->event = EventStatus::tryFrom($response['event']) ?? throw new InvalidArgumentException("Unknown event type: {$response['event']}");
        $this->timestamp = Carbon::parse($response['timestamp']);
    }
}
