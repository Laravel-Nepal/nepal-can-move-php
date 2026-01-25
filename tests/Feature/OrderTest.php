<?php

declare(strict_types=1);

use AchyutN\NCM\Data\Order\CreateOrderRequest;
use AchyutN\NCM\Data\Order\Order;
use AchyutN\NCM\NCM;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

it('can create an order with mocked response', function () {
    $mockBody = json_encode([
        'message' => 'Order created successfully',
        'orderid' => 747,
        'weight' => 1.00,
        'delivery_charge' => 199.00,
        'delivery_type' => 'Door2Door',
    ]);

    $mock = new MockHandler([
        new Response(200, [], $mockBody),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $guzzle = new Client(['handler' => $handlerStack]);

    $ncm = new NCM('fake-token', 'https://demo.nepalcanmove.com/api/', $guzzle);

    $request = new CreateOrderRequest(
        name: 'Achyut Neupane',
        phone: '9860323771',
        codCharge: '150',
        address: 'Lakeside',
        fbranch: 'POKHARA',
        branch: 'TINKUNE',
        package: 'Books',
        vrefId: 'VREF12345',
        instruction: 'Handle with care',
    );

    $order = $ncm->createOrder($request);

    expect($order)->toBeInstanceOf(Order::class)
        ->and($order->orderid)->toBe(747)
        ->and($order->deliveryCharge)->toBe(199.0);
});
