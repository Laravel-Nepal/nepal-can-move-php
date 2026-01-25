<?php

declare(strict_types=1);

use AchyutN\NCM\Data\Order\CreateOrderRequest;
use AchyutN\NCM\Exceptions\NCMException;

beforeEach(function () {
    $this->createOrderRequest = new CreateOrderRequest(
        name: 'Achyut Neupane',
        phone: '9860323771',
        codCharge: '150',
        address: 'Lakeside',
        fbranch: 'POKHARA',
        branch: 'TINKUNE',
        package: 'Books',
        vrefId: 'VREF12345',
        instruction: 'Handle with care',
        deliveryType: 'Door2Door',
        weight: '1',
    );

    if (getenv('NCM_TOKEN') === false) {
        $this->markTestSkipped('NCM_TOKEN not defined in ENV.');
    }
});

it('fails to create an order with invalid branch', function () {
    $ncm = ncm();

    $this->createOrderRequest->branch = 'INVALID_BRANCH_NAME';

    $this->expectException(NCMException::class);

    $ncm->createOrder($this->createOrderRequest);
});

it('can create an order on demo environment', function () {
    $ncm = ncm();

    $order = $ncm->createOrder($this->createOrderRequest);

    expect($order)->toBeValidOrder();
});

it('can fetch an order by ID', function () {
    $ncm = ncm();

    $order = $ncm->createOrder($this->createOrderRequest);

    expect($order)->toBeValidOrder();

    $fetchedOrder = $ncm->getOrder($order->orderid);

    expect($fetchedOrder)->toBeValidOrder()
        ->and($fetchedOrder->orderid)->toBe($order->orderid);

})->only();
