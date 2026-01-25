<?php

declare(strict_types=1);

use AchyutN\NCM\Data\Order\Order;
use AchyutN\NCM\Exceptions\NCMException;
use AchyutN\NCM\NCM;

uses()->in('Feature');

function ncm(): NCM
{
    $apiKey = getenv('NCM_TOKEN') ?: 'fake-token';

    if (!$apiKey) {
        throw new NCMException('NCM API token is not set in testing environment.');
    }

    $baseUrl = 'https://demo.nepalcanmove.com/api/';

    return new NCM($apiKey, $baseUrl);
}

expect()->extend('toBeValidOrder', function () {
    return $this->toBeInstanceOf(Order::class)
        ->toHaveKey('orderid')
        ->orderid->toBeInt()->toBeGreaterThan(0);
});
