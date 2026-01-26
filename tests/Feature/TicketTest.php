<?php

declare(strict_types=1);

it('throws an exception on wrong ticket type', function () {
    $ncm = ncm();

    $this->expectException(\AchyutN\NCM\Exceptions\NCMException::class);

    $ncm->createSupportTicket('InvalidType', 'This is error ticket.');
})->only();

it('can create a support ticket', function () {
    $ncm = ncm();

    $result = $ncm->createSupportTicket('General', 'This is a test support ticket created from SDK.');

    expect($result)->toBeTrue();
});
