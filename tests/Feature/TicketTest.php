<?php

declare(strict_types=1);

use AchyutN\NCM\Exceptions\NCMException;

it('throws an exception on wrong ticket type', function () {
    $ncm = ncm();

    $this->expectException(NCMException::class);

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

    it('can create a ticket', function () use ($ticketId) {
        expect($ticketId)->toBeInt()->toBeGreaterThan(0);
    });

    it('throws an exception on second ticket', function () use ($ncm) {
        $this->expectException(NCMException::class);

        $ncm->createCODTransferTicket(
            bankName: 'Test Bank',
            accountHolderName: 'Achyut Neupane',
            accountNumber: '1234567890'
        );
    });

    it('can create another ticket after closing first', function () use ($ncm, $ticketId) {
        $ncm->closeTicket($ticketId);

        $newTicketId = $ncm->createCODTransferTicket(
            bankName: 'Test Bank',
            accountHolderName: 'Achyut Neupane',
            accountNumber: '1234567890'
        );

        expect($newTicketId)->toBeInt()->toBeGreaterThan(0)
            ->and($newTicketId)->not->toBe($ticketId);
    });

    it('clears ticket after tests', function () use ($ncm, $ticketId) {
        $response = $ncm->closeTicket($ticketId);

        expect($response)->toBeTrue();
    });
});
