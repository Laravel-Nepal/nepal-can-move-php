<?php

declare(strict_types=1);

namespace LaravelNepal\NCM;

use GuzzleHttp\Client;
use LaravelNepal\NCM\Exceptions\NCMException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

final class NCMClient
{
    public const USER_AGENT = 'NepalCanMovePHPSDK';

    public Client $client;

    public function __construct(
        ?string $apiKey = null,
        ?string $baseUri = null,
        ?Client $client = null
    ) {
        $baseUri = rtrim((string) $baseUri, '/').'/';

        $this->client = $client ?? new Client([
            'base_uri' => $baseUri,
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Token '.$apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => self::USER_AGENT,
            ],
        ]);
    }

    /**
     * @throws NCMException
     */
    public function get(string $uri, array $query = []): array // @phpstan-ignore-line
    {
        return $this->request('GET', $uri, ['query' => $query]);
    }

    /**
     * @throws NCMException
     */
    public function post(string $uri, array $payload = []): array // @phpstan-ignore-line
    {
        return $this->request('POST', $uri, ['json' => $payload]);
    }

    /**
     * @throws NCMException
     */
    private function request(string $method, string $endpoint, array $options = []): array // @phpstan-ignore-line
    {
        try {
            $response = $this->client->request($method, ltrim($endpoint, '/'), $options);

            if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
                $this->fail($response);
            }

            $responseBody = (string) $response->getBody();
        } catch (Throwable $throwable) {
            throw new NCMException($throwable->getMessage(), $throwable->getCode(), $throwable);
        }

        return json_decode($responseBody, true) ?: []; // @phpstan-ignore-line
    }

    /**
     * @throws NCMException
     */
    private function fail(ResponseInterface $response): void
    {
        $body = json_decode((string) $response->getBody(), true);

        if (is_null($body)) {
            throw new NCMException('NepalCanMove API error: '.$response->getReasonPhrase(), $response->getStatusCode());
        }

        $bodyArray = (array) $body;

        foreach (['Error', 'message', 'detail'] as $key) {
            if (! is_null($bodyArray[$key] ?? null)) {
                throw new NCMException($this->formatError($bodyArray[$key]), $response->getStatusCode());
            }
        }
    }

    private function formatError(mixed $error): string
    {
        if (is_string($error)) {
            return $error;
        }

        if (is_array($error)) {
            /** @var array<string, mixed> $error */
            return implode(', ', array_map(
                fn ($key, mixed $val): string => (string) match (true) {
                    is_array($val) => $key.': '.implode(', ', $val),
                    is_object($val) => json_encode($val),
                    is_string($val), is_numeric($val) => "{$key}: {$val}",
                    default => 'Unexpected error format',
                },

                array_keys($error),
                $error
            ));
        }

        return 'An unknown error occurred.';
    }
}
