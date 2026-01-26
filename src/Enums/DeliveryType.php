<?php

declare(strict_types=1);

namespace AchyutN\NCM\Enums;

enum DeliveryType: string
{
    case DoorToDoor = 'Door2Door';
    case BranchToDoor = 'Branch2Door';
    case DoorToBranch = 'Door2Branch';
    case BranchToBranch = 'Branch2Branch';

    /**
     * Map the order creation response to DeliveryType enum
     */
    public static function fromOrderCreateValue(string $value): ?self
    {
        return match ($value) {
            'Pickup/Collect' => self::DoorToDoor,
            'Send' => self::BranchToDoor,
            'D2B' => self::DoorToBranch,
            'B2B' => self::BranchToBranch,
            default => null,
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::DoorToDoor => 'Door to Door',
            self::BranchToDoor => 'Branch to Door',
            self::DoorToBranch => 'Door to Branch',
            self::BranchToBranch => 'Branch to Branch',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::DoorToDoor => 'NCM pickup & delivery. Full base charge.',
            self::BranchToDoor => 'Sender drops at branch, NCM delivers at door. Full base charge.',
            self::DoorToBranch => 'NCM pick, Customer collect at branch. Base charge - 50.',
            self::BranchToBranch => 'Sender Drop at branch & customer collect at branch. Base charge - 50.',
        };
    }

    /**
     * Mapping for the /v1/shipping-rate endpoint
     */
    public function toShippingRateValue(): string
    {
        return match ($this) {
            self::DoorToDoor => 'Pickup/Collect',
            self::BranchToDoor => 'Send',
            self::DoorToBranch => 'D2B',
            self::BranchToBranch => 'B2B',
        };
    }

    /**
     * Mapping for the /v1/order/create endpoint
     */
    public function toOrderCreateValue(): string
    {
        return $this->value;
    }
}
