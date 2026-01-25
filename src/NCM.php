<?php

declare(strict_types=1);

namespace AchyutN\NCM;

use AchyutN\NCM\Exceptions\NCMException;
use AchyutN\NCM\Managers\OrderManager;
use GuzzleHttp\Client;
use Illuminate\Support\Traits\ForwardsCalls;

final class NCM
{
    use ForwardsCalls;

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
