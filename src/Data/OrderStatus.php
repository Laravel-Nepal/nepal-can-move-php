<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data;

use AchyutN\NCM\Enums\OrderStatus as OrderStatusEnum;
use Illuminate\Support\Carbon;

/**
 * @phpstan-type OrderStatusData array{
 *      orderid: int,
 *      status: string,
 *      added_time?: string|null,
 *      vendor_return?: bool|null
 * }
 *
 * @template-extends BaseData<OrderStatusData>
 */
final class OrderStatus extends BaseData
{
    public int $orderId;

    public OrderStatusEnum $status;

    public ?Carbon $addedTime = null;

    public ?bool $vendorReturn = null;

    protected function fromResponse(array $response): void
    {
        $this->orderId = $response['orderid'];
        $this->status = OrderStatusEnum::from($response['status']);
        $this->addedTime = isset($response['added_time']) ? Carbon::parse($response['added_time']) : null;

        if (isset($response['vendor_return'])) {
            $vendorReturn = $response['vendor_return'];
            $this->vendorReturn = is_bool($vendorReturn) ? $vendorReturn : null;
        }
    }
}
