<?php

declare(strict_types=1);

use AchyutN\NCM\Data\Branch;
use Illuminate\Support\Collection;

it('fetches all branches successfully', function () {
    $ncm = ncm();

    $branches = $ncm->getBranches();

    expect($branches)->toBeInstanceOf(Collection::class)
        ->and($branches->first())->toBeInstanceOf(Branch::class);
});
