<?php

declare(strict_types=1);

use AchyutN\NCM\Data\Comment;
use AchyutN\NCM\Data\CreateOrderRequest;
use AchyutN\NCM\Data\OrderStatus;
use AchyutN\NCM\Exceptions\NCMException;
use Illuminate\Support\Collection;

beforeEach(function () {
    if (getenv('NCM_TOKEN') === false) {
        $this->markTestSkipped('NCM_TOKEN not defined in ENV.');
    }
});

it('fails to create an order with invalid branch', function () {
    $ncm = ncm();

    $this->expectException(NCMException::class);

    $ncm->createOrder(new CreateOrderRequest(
        name: 'INVALID_NAME',
        phone: 'INVALID_NUMBER',
        codCharge: 'INVALID_CHARGE',
        address: 'INVALID_ADDRESS',
        fbranch: 'INVALID_BRANCH',
        branch: 'INVALID_BRANCH',
    ));
});

/** @throws NCMException */
describe('order', function () {
    $ncm = ncm();

    $createOrderRequest = new CreateOrderRequest(
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

    $order = $ncm->createOrder($createOrderRequest);

    it('can create an order', function () use ($order) {
        expect($order)->toBeValidOrder();
    });

    it('can fetch an order by ID', function () use ($ncm, $order) {
        $fetchedOrder = $ncm->getOrder($order->orderid);

        expect($fetchedOrder)->toBeValidOrder()
            ->and($fetchedOrder->orderid)->toBe($order->orderid);
    });

    it('returns collection of status', function () use ($order) {
        $statusCollection = $order->status();

        expect($statusCollection)
            ->toBeInstanceOf(Collection::class)
            ->and($statusCollection->first())->toBeInstanceOf(OrderStatus::class);
    });

    it('can fetch statuses for multiple orders', function () use ($ncm, $order) {
        $statuses = $ncm->getOrdersStatuses([$order->orderid]);

        expect($statuses)
            ->toBeInstanceOf(Collection::class)
            ->and($statuses->first())
            ->toBeInstanceOf(OrderStatus::class);
    });

    it('can add comment to an order', function () use ($order) {
        $commentResponse = $order->addComment('I created this test comment from PHP SDK.');

        expect($commentResponse)->toBeTrue();
    });

    it('can fetch comments of an order', function () use ($order) {
        $comments = $order->comments();

        expect($comments)
            ->toBeInstanceOf(Collection::class)
            ->toHaveCount(1)
            ->and($comments->first())
            ->toBeInstanceOf(Comment::class);
    });

    it('can fetch last 25 comments of orders', function () use ($ncm) {
        $comments = $ncm->getOrdersComments();

        if ($comments->isEmpty()) {
            $this->markTestSkipped('No comments found for any orders.');
        } else {
            expect($comments)
                ->toBeInstanceOf(Collection::class)
                ->and($comments->first())
                ->toBeInstanceOf(Comment::class);
        }
    });
});
