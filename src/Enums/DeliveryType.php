<?php

declare(strict_types=1);

namespace AchyutN\NCM\Enums;

enum DeliveryType: string
{
    case PickupOrCollect = 'Pickup/Collect';
    case BranchToDoor = 'Send';
    case DoorToBranch = 'D2B';
    case BranchToBranch = 'B2B';

    public function getLabel(): string
    {
        return match ($this) {
            self::PickupOrCollect => 'Door to Door',
            self::BranchToDoor => 'Branch to Door',
            self::DoorToBranch => 'Door to Branch',
            self::BranchToBranch => 'Branch to Branch',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::PickupOrCollect => 'NCM pickup & delivery. Full base charge.',
            self::BranchToDoor => 'Sender drops at branch, NCM delivers at door. Full base charge.',
            self::DoorToBranch => 'NCM pick, Customer collect at branch. Base charge - 50.',
            self::BranchToBranch => 'Sender Drop at branch & customer collect at branch. Base charge - 50.',
        };
    }
}
