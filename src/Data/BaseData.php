<?php

declare(strict_types=1);

namespace AchyutN\NCM\Data;

use AchyutN\NCM\NCM;

/** @template T of array */
abstract class BaseData
{
    /** @param T $attributes */
    public function __construct(protected array $attributes, protected NCM $ncm)
    {
        $this->fromResponse($this->attributes);
    }

    /** @param T $response */
    abstract protected function fromResponse(array $response): void;

    protected function camelCase(string $key): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $key))));
    }
}
