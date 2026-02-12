<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Enums;

enum EventStatus: string
{
    case PickupCompleted = 'pickup_completed';
    case SentForDelivery = 'sent_for_delivery';
    case Dispatched = 'order_dispatched';
    case Arrived = 'order_arrived';
    case Delivered = 'delivery_completed';

    public function getLabel(): string
    {
        return match ($this) {
            self::PickupCompleted => 'Pickup Completed',
            self::SentForDelivery => 'Sent for Delivery',
            self::Dispatched => 'Dispatched',
            self::Arrived => 'Arrived',
            self::Delivered => 'Delived',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::PickupCompleted => 'Order has been picked up from the origin.',
            self::SentForDelivery => 'Order has been sent out for delivery.',
            self::Dispatched => 'Order has been dispatched from origin branch.',
            self::Arrived => 'Order has arrived at destination branch.',
            self::Delivered => 'Order has been successfully delivered.',
        };
    }
}
