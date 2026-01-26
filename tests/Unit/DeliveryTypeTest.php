<?php

declare(strict_types=1);

use AchyutN\NCM\Enums\DeliveryType;

it('has correct order create response value', function (string $input, DeliveryType $expected) {
    expect(DeliveryType::fromOrderCreateValue($input))->toBe($expected);
})->with([
    ['Pickup/Collect', DeliveryType::DoorToDoor],
    ['Send', DeliveryType::BranchToDoor],
    ['D2B', DeliveryType::DoorToBranch],
    ['B2B', DeliveryType::BranchToBranch],
]);

it('returns null for unknown order create values', function () {
    expect(DeliveryType::fromOrderCreateValue('InvalidType'))->toBeNull();
});

it('maps correctly to shipping rate values', function (DeliveryType $type, string $expected) {
    expect($type->toShippingRateValue())->toBe($expected);
})->with([
    [DeliveryType::DoorToDoor, 'Pickup/Collect'],
    [DeliveryType::BranchToDoor, 'Send'],
    [DeliveryType::DoorToBranch, 'D2B'],
    [DeliveryType::BranchToBranch, 'B2B'],
]);

it('maps correctly to order create values', function (DeliveryType $type, string $expected) {
    expect($type->toOrderCreateValue())->toBe($expected);
})->with([
    [DeliveryType::DoorToDoor, 'Door2Door'],
    [DeliveryType::BranchToDoor, 'Branch2Door'],
    [DeliveryType::DoorToBranch, 'Door2Branch'],
    [DeliveryType::BranchToBranch, 'Branch2Branch'],
]);

it('has correct labels and descriptions', function (DeliveryType $type, string $label, string $description) {
    expect($type->getLabel())->toBe($label);
    expect($type->getDescription())->toContain($description);
})->with([
    [DeliveryType::DoorToDoor, 'Door to Door', 'NCM pickup & delivery'],
    [DeliveryType::BranchToDoor, 'Branch to Door', 'Sender drops at branch'],
    [DeliveryType::DoorToBranch, 'Door to Branch', 'Customer collect at branch'],
    [DeliveryType::BranchToBranch, 'Branch to Branch', 'Sender Drop at branch'],
]);
