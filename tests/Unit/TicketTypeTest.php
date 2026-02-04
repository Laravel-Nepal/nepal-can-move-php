<?php

declare(strict_types=1);

use LaravelNepal\NCM\Enums\TicketType;

test('it has the correct string values for the API', function (TicketType $type, string $expected) {
    expect($type->value)->toBe($expected);
})->with([
    [TicketType::General, 'General'],
    [TicketType::OrderProcessing, 'Order Processing'],
    [TicketType::Return, 'Return'],
    [TicketType::Pickup, 'Pickup'],
]);

test('it returns the correct labels', function (TicketType $type, string $expected) {
    expect($type->getLabel())->toBe($expected);
})->with([
    [TicketType::General, 'General'],
    [TicketType::OrderProcessing, 'Order Processing'],
    [TicketType::Return, 'Return'],
    [TicketType::Pickup, 'Pickup'],
]);

test('it returns the correct descriptions from documentation', function (TicketType $type, string $expected) {
    expect($type->getDescription())->toBe($expected);
})->with([
    [TicketType::General, 'General inquiries or issues'],
    [TicketType::OrderProcessing, 'Order processing related issues'],
    [TicketType::Return, 'Return/refund related requests'],
    [TicketType::Pickup, 'Pickup scheduling or issues'],
]);

test('it can be instantiated from a valid string', function (string $value, TicketType $expected) {
    expect(TicketType::from($value))->toBe($expected);
})->with([
    ['General', TicketType::General],
    ['Order Processing', TicketType::OrderProcessing],
    ['Return', TicketType::Return],
    ['Pickup', TicketType::Pickup],
]);
