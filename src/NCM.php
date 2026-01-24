<?php

declare(strict_types=1);

namespace AchyutN\NCM;

use AchyutN\NCM\Exceptions\NCMException;
use GuzzleHttp\Client;

final class NCM
{
    /**
     * The NepalCanMove client instance.
     */
    private NCMClient $client;

    /**
     * @throws NCMException
     */
    public function __construct(
        string $apiKey,
        ?string $baseUri = null,
        ?Client $guzzle = null
    ) {
        if (trim($apiKey) === '') {
            throw new NCMException('API key is required to communicate with NepalCanMove.');
        }

        $this->client = new NCMClient($apiKey, $baseUri, $guzzle);
    }

    /**
     * Transform the items of the collection to the given class.
     */
    private function transform(array $collection, string $class): array
    {
        return array_map(function ($attributes) use ($class) {
            return new $class($attributes, $this);
        }, $collection);
    }
}
