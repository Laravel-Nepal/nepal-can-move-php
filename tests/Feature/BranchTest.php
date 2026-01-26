<?php

declare(strict_types=1);

use AchyutN\NCM\Data\Branch;
use AchyutN\NCM\Enums\DeliveryType;
use Illuminate\Support\Collection;

it('fetches all branches successfully', function () {
    $ncm = ncm();

    $branches = $ncm->getBranches();

    expect($branches)->toBeInstanceOf(Collection::class)
        ->and($branches->first())->toBeInstanceOf(Branch::class);
});

it('fetches the delivery charge between branches', function () {
    $ncm = ncm();

    $branches = $ncm->getBranches();

    if ($branches->count() < 2) {
        $this->markTestSkipped('Not enough branches to test delivery charge.');
    }

    $source = $branches->first();
    $destination = $branches->skip(1)->first();

    expect($source)->toBeInstanceOf(Branch::class)
        ->and($destination)->toBeInstanceOf(Branch::class);

    $charge = $ncm->getDeliveryCharge($source, $destination, DeliveryType::PickupOrCollect);

    expect($charge)->toBeGreaterThanOrEqual(0);
});
