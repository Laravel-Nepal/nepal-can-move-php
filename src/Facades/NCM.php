<?php

declare(strict_types=1);

namespace AchyutN\NCM\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AchyutN\NCM\NCM
 */
final class NCM extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return \AchyutN\NCM\NCM::class;
    }
}
