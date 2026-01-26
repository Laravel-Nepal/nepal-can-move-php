<?php

declare(strict_types=1);

namespace AchyutN\NCM\Managers;

use AchyutN\NCM\Exceptions\NCMException;

trait TicketManager
{
    /**
     * Create a general support ticket.
     *
     * @throws NCMException
     */
    public function createSupportTicket(string $type, string $message): bool
    {
        $this->client->post('/v2/vendor/ticket/create', [
            'ticket_type' => $type,
            'message' => $message,
        ]);

        return true;
    }
}
