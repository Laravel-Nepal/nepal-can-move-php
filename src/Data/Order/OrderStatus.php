<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data\Order;

use AchyutN\NCM\Data\BaseData;
use Illuminate\Support\Carbon;

/**
 * @phpstan-type OrderStatusData array{
 *      orderid: int,
 *      status: string,
 *      added_time: string|null,
 *      vendor_return: bool|null
 * }
 *
 * @template-extends BaseData<OrderStatusData>
 */
final class OrderStatus extends BaseData
{
    public int $orderid;

    public string $status;

    public ?Carbon $addedTime = null;

    public ?bool $vendorReturn = null;

    protected function fromResponse(array $response): void
    {
        $this->orderid = $response['orderid'];
        $this->status = $response['status'];
        $this->addedTime = isset($response['added_time']) ? Carbon::parse($response['added_time']) : null;

        $this->vendorReturn = isset($response['vendor_return']) ? filter_var($response['vendor_return'], FILTER_VALIDATE_BOOLEAN) : null;
    }
}
