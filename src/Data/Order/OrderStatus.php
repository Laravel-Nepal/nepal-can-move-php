<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data\Order;

use AchyutN\NCM\Data\BaseData;
use Carbon\Carbon;

/**
 * @phpstan-type OrderStatusData array{
 *      orderid: int,
 *      status: string,
 *      added_time: string,
 *      vendor_return: string
 * }
 *
 * @template-extends BaseData<OrderStatusData>
 */
final class OrderStatus extends BaseData
{
    public int $orderid;

    public string $status;

    public Carbon $addedTime;

    public bool $vendorReturn;

    protected function fromResponse(array $response): void
    {
        $this->orderid = $response['orderid'];
        $this->status = $response['status'];
        $this->addedTime = Carbon::parse($response['added_time']);

        $this->vendorReturn = filter_var($response['vendor_return'], FILTER_VALIDATE_BOOLEAN);
    }
}
