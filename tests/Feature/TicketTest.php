<?php

declare(strict_types=1);

it('throws an exception on wrong ticket type', function () {
    $ncm = ncm();

    $this->expectException(\AchyutN\NCM\Exceptions\NCMException::class);

    $ncm->createSupportTicket('InvalidType', 'This is error ticket.');
});

it('can create a support ticket', function () {
    $ncm = ncm();

    $result = $ncm->createSupportTicket('General', 'This is a test support ticket created from SDK.');

    expect($result)->toBeTrue();
});

describe('COD transfer ticket', function () {
    $ncm = ncm();

    $ticketId = $ncm->createCODTransferTicket(
        bankName: 'Test Bank',
        accountHolderName: 'Achyut Neupane',
        accountNumber: '1234567890'
    );

    it('can create a COD transfer ticket', function () use ($ticketId) {
        expect($ticketId)->toBeInt()->toBeGreaterThan(0);
    });

    it('throws an exception on second COD ticket', function () use ($ncm) {
        $this->expectException(\AchyutN\NCM\Exceptions\NCMException::class);

        $ncm->createCODTransferTicket(
            bankName: 'Test Bank',
            accountHolderName: 'Achyut Neupane',
            accountNumber: '1234567890'
        );
    });
});
