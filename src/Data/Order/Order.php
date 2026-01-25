<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data\Order;

use AchyutN\NCM\Data\BaseData;

final class Order extends BaseData
{
    /**
     * The unique ID of the order.
     */
    public int $orderid;

    /**
     * The message associated with the order creation.
     */
    public string $message;

    /**
     * The weight of the order.
     */
    public float $weight;

    /**
     * The delivery charge for the order.
     */
    public float $deliveryCharge;

    /**
     * The delivery type of the order.
     */
    public string $deliveryType;
}
