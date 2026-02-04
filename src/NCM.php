<?php

declare(strict_types=1);

namespace LaravelNepal\NCM;

use LaravelNepal\NCM\Exceptions\NCMException;
use LaravelNepal\NCM\Managers\HasManagers;
use GuzzleHttp\Client;
use Illuminate\Support\Traits\ForwardsCalls;

final class NCM
{
    use ForwardsCalls;
    use HasManagers;

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
     * Magic method to forward calls to the NCMClient instance.
     */
    public function __call(string $method, array $parameters) // @phpstan-ignore-line
    {
        return $this->forwardCallTo($this->client, $method, $parameters);
    }
}
