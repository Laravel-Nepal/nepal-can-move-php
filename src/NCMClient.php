<?php

declare(strict_types=1);

namespace AchyutN\NCM;

use AchyutN\NCM\Exceptions\NCMException;
use GuzzleHttp\Client;
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

        if (! is_null($bodyArray['Error'])) {
            throw new NCMException($this->formatError($bodyArray['Error']), $response->getStatusCode());
        }

        throw new NCMException($this->formatError($bodyArray['detail'] ?? null), $response->getStatusCode());
    }

    private function formatError(mixed $error): string
    {
        if (is_string($error)) {
            return $error;
        }

        if (is_array($error)) {
            /** @var array<string, string> $error */
            return implode(', ', array_map(
                fn ($key, string $val): string => is_numeric($key) ? $val : "{$key}: {$val}",
                array_keys($error),
                $error
            ));
        }

        return 'An unknown error occurred.';
    }
}
