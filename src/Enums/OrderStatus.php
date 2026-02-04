<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Enums;

enum OrderStatus: string
{
    case DropOffOrderCreated = 'Drop off Order Created';
    case OrderCreated = 'Order Created';
    case PickupOrderCreated = 'Pickup Order Created';
    case SentForPickup = 'Sent for Pickup';
    case DropOffOrderCollected = 'Drop off Order Collected';
    case PickupComplete = 'Pickup Complete';
    case Dispatched = 'Dispatched';
    case Arrived = 'Arrived';
    case ReturnedToWarehouse = 'Returned to Warehouse';
    case SentForDelivery = 'Sent for Delivery';
    case Delivered = 'Delivered';
    case Cancelled = 'Cancelled';
    case SentToVendor = 'Sent to Vendor';

    public function getLabel(): string
    {
        return $this->value;
    }
}
