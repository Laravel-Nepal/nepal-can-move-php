<?php

declare(strict_types=1);

use AchyutN\NCM\Data\Comment;
use AchyutN\NCM\Data\CreateOrderRequest;
use AchyutN\NCM\Data\OrderStatus;
use AchyutN\NCM\Data\RedirectOrderRequest;
use AchyutN\NCM\Enums\DeliveryType;
use AchyutN\NCM\Enums\OrderStatus as OrderStatusEnum;
use AchyutN\NCM\Exceptions\NCMException;
use Illuminate\Support\Collection;

beforeEach(function () {
    if (getenv('NCM_TOKEN') === false) {
        $this->markTestSkipped('NCM_TOKEN not defined in ENV.');
    }
});

it('fails to create an order with invalid values', function () {
    $ncm = ncm();

    $this->expectException(NCMException::class);

    $ncm->createOrder(new CreateOrderRequest(
        name: 'INVALID_NAME',
        phone: 'INVALID_NUMBER',
        codCharge: 'INVALID_CHARGE',
        address: 'INVALID_ADDRESS',
        sourceBranch: 'INVALID_BRANCH',
        destinationBranch: 'INVALID_BRANCH',
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
        sourceBranch: 'POKHARA',
        destinationBranch: 'TINKUNE',
        package: 'Books',
        orderIdentifier: 'SDK'.time(),
        instruction: 'Handle with care',
        deliveryType: DeliveryType::BranchToBranch,
        weight: '1',
    );

    $order = $ncm->createOrder($createOrderRequest);

    it('can create an order', function () use ($order) {
        expect($order)->toBeValidOrder();
    });

    it('can fetch an order by ID', function () use ($ncm, $order) {
        $fetchedOrder = $ncm->getOrder($order->id);

        expect($fetchedOrder)->toBeValidOrder()
            ->and($fetchedOrder->id)->toBe($order->id);
    });

    it('returns order status', function () use ($order) {
        $status = $order->status();

        expect($status)
            ->toBeInstanceOf(OrderStatus::class);
    });

    it('can fetch statuses for multiple orders', function () use ($ncm, $order) {
        $statuses = $ncm->getOrdersStatuses([$order->id]);

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

    it('can mark order for return process', function () use ($ncm, $order) {
        $status = $order->status()->status;

        if (! in_array($status, [OrderStatusEnum::Arrived, OrderStatusEnum::PickupComplete, OrderStatusEnum::ReturnedToWarehouse])) {
            $this->expectException(NCMException::class);
        }

        $response = $order->return('Customer not available');

        expect($response)->toBeTrue();
    });

    it('can mark order for exchange process', function () use ($ncm, $order) {
        $status = $order->status()->status;

        if ($status !== OrderStatusEnum::Delivered) {
            $this->expectException(NCMException::class);
        }

        $response = $order->exchange();

        expect($response)->toBeTrue();
    });

    it('can mark order for redirect process', function () use ($ncm, $order) {
        $redirectOrderRequest = new RedirectOrderRequest(
            orderId: $order->id,
            name: 'Achyut Neupane (Updated)',
            phone: '9804087870',
            address: 'New Address, Pokhara',
            orderIdentifier: 'NEW'.time(),
            destinationBranchId: 1,
            codCharge: 200.0,
        );

        $status = $order->status()->status;

        if (! in_array($status, [OrderStatusEnum::Arrived, OrderStatusEnum::PickupComplete, OrderStatusEnum::ReturnedToWarehouse])) {
            $this->expectException(NCMException::class);
        }

        $response = $order->redirect($redirectOrderRequest);

        expect($response)->toBeTrue();
    });
});
