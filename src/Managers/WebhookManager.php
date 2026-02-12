<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Managers;

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
        return $this->client->post('/vendor/webhook', [
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
        return $this->client->post('/vendor/webhook', [
            'webhook_url' => null,
        ])->successful();
    }

    /**
     * Test whether the webhook URL is valid and can receive updates.
     *
     * @throws NCMException
     */
    public function testWebhookUrl(string $url): bool
    {
        return $this->client->post('/vendor/webhook/test', [
            'webhook_url' => $url,
        ])->successful();
    }
}
