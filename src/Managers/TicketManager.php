<?php

declare(strict_types=1);

namespace AchyutN\NCM\Managers;

use AchyutN\NCM\Enums\TicketType;
use AchyutN\NCM\Exceptions\NCMException;

trait TicketManager
{
    /**
     * Create a general support ticket.
     *
     * @throws NCMException
     */
    public function createSupportTicket(TicketType $ticketType, string $message): bool
    {
        $this->client->post('/v2/vendor/ticket/create', [
            'ticket_type' => $ticketType->value,
            'message' => $message,
        ]);

        return true;
    }

    /**
     * Create a Cash on Delivery (COD) transfer request ticket.
     *
     * @return int The ticket ID created for the COD transfer request.
     *
     * @throws NCMException
     */
    public function createCODTransferTicket(
        string $bankName,
        string $accountHolderName,
        string $accountNumber,
    ): int {
        /** @var array{'message': string, 'ticket': int} $response */
        $response = $this->client->post('/v2/vendor/ticket/cod/create', [
            'bankName' => $bankName,
            'bankAccountName' => $accountHolderName,
            'bankAccountNumber' => $accountNumber,
        ]);

        return $response['ticket'];
    }

    /**
     * Close a ticket by its ID.
     *
     * @throws NCMException
     */
    public function closeTicket(int $ticketId): bool
    {
        $this->client->post('/v2/vendor/ticket/close/'.$ticketId);

        return true;
    }
}
