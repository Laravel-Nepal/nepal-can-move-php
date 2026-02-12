<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Managers;

use LaravelNepal\NCM\Data\StatusEvent;
use LaravelNepal\NCM\Exceptions\NCMException;

trait WebhookManager
{
    /**
     * Set the webhook URL for receiving order status updates.
     *
     * @throws NCMException
     */
    public function setWebhookUrl(string $url): bool
    {
        return $this->client->post('/v2/vendor/webhook', [
            'webhook_url' => $url,
        ])->successful();
    }

    /**
     * Remove the webhook URL, stopping order status updates.
     *
     * @throws NCMException
     */
    public function removeWebhookUrl(): bool
    {
        return $this->setWebhookUrl('');
    }

    /**
     * Test whether the webhook URL is valid and can receive updates.
     *
     * @throws NCMException
     */
    public function testWebhookUrl(string $url): bool
    {
        return $this->client->post('/v2/vendor/webhook/test', [
            'webhook_url' => $url,
        ])->successful();
    }

    /**
     * Parse raw incoming request data into a typed DTO.
     */
    public function parseWebhook(array $response): StatusEvent
    {
        return new StatusEvent($response, $this);
    }
}
