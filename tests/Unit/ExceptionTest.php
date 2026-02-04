<?php

declare(strict_types=1);

use LaravelNepal\NCM\Exceptions\NCMException;
use LaravelNepal\NCM\NCM;

test('it throws an exception if the api key is empty', function () {
    new NCM(apiKey: '');
})->throws(NCMException::class, 'API key is required to communicate with NepalCanMove.');
