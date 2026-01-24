<?php

declare(strict_types=1);

namespace AchyutN\NCM\Providers;

use Illuminate\Support\ServiceProvider;

final class NCMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/nepal-can-move.php', 'nepal-can-move');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/config/nepal-can-move.php' => $this->app->configPath('nepal-can-move.php'),
            ], 'nepal-can-move');
        }
    }
}
