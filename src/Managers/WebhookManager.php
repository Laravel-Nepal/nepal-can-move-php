<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Managers;

use LaravelNepal\NCM\Data\StatusEvent;
use LaravelNepal\NCM\Exceptions\NCMException;

/** @phpstan-import-type StatusEventData from StatusEvent */
trait WebhookManager
{
    /**
     * Set the webhook URL for receiving order status updates.
     */
    public function setWebhookUrl(string $url): bool
    {
        try {
            $this->client->post('/v2/vendor/webhook', [
                'webhook_url' => $url,
            ]);

            return true;
        } catch (NCMException) {
            return false;
        }
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
     */
    public function testWebhookUrl(string $url): bool
    {
        try {
            /** @var StatusEventData $response */
            $response = $this->client->post('/v2/vendor/webhook/test', [
                'webhook_url' => $url,
            ]);

            return trim($response['order_id'] ?? '') !== '';
        } catch (NCMException) {
            return false;
        }
    }

    /**
     * Parse raw incoming request data into a typed DTO.
     *
     * @param  StatusEventData  $response
     */
    public function parseWebhook(array $response): StatusEvent
    {
        return new StatusEvent($response, $this);
    }
}
