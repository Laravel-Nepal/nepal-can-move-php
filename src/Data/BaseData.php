<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Data;

use LaravelNepal\NCM\NCM;

/** @template T of array */
abstract class BaseData
{
    /** @param T $attributes */
    public function __construct(array $attributes, protected NCM $ncm)
    {
        $this->fromResponse($attributes);
    }

    /** @param T $response */
    abstract protected function fromResponse(array $response): void;
}
