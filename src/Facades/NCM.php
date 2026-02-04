<?php

declare(strict_types=1);

namespace LaravelNepal\NCM\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LaravelNepal\NCM\NCM
 */
final class NCM extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return \LaravelNepal\NCM\NCM::class;
    }
}
