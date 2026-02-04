<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Enums;

enum TicketType: string
{
    case General = 'General';
    case OrderProcessing = 'Order Processing';
    case Return = 'Return';
    case Pickup = 'Pickup';

    public function getLabel(): string
    {
        return match ($this) {
            self::General => 'General',
            self::OrderProcessing => 'Order Processing',
            self::Return => 'Return',
            self::Pickup => 'Pickup',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::General => 'General inquiries or issues',
            self::OrderProcessing => 'Order processing related issues',
            self::Return => 'Return/refund related requests',
            self::Pickup => 'Pickup scheduling or issues',
        };
    }
}
