<?php

declare(strict_types=1);

use AchyutN\NCM\Exceptions\NCMException;
use AchyutN\NCM\NCM;

test('it throws an exception if the api key is empty', function () {
    new NCM(apiKey: '');
})->throws(NCMException::class, 'API key is required to communicate with NepalCanMove.');
