<?php

declare(strict_types=1);

namespace AchyutN\NCM\Providers;

use AchyutN\NCM\Exceptions\NCMException;
use AchyutN\NCM\NCM;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class NCMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/nepal-can-move.php', 'nepal-can-move');

        $this->setupNCM();
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/nepal-can-move.php' => $this->app->configPath('nepal-can-move.php'),
            ], 'nepal-can-move');
        }
    }

    private function setupNCM(): void
    {
        $this->app->singleton(
            NCM::class,
            function (Application $app): NCM {
                /** @var Repository $config */
                $config = $app->make('config');

                /**
                 * @var ?string $token
                 */
                $token = $config->get('nepal-can-move.token');

                $this->ensureTokenIsPresent($token);

                /**
                 * @var bool $sandboxMode
                 */
                $sandboxMode = $config->get('nepal-can-move.sandbox_mode', false);

                /**
                 * @var array{'demo': string, 'live': string} $baseUrls
                 */
                $baseUrls = $config->get('nepal-can-move.base_urls', []);

                $baseUri = $sandboxMode
                    ? ($baseUrls['demo'] ?? null)
                    : ($baseUrls['live'] ?? null);

                return new NCM((string) $token, $baseUri);
            }
        );
    }

    private function ensureTokenIsPresent(?string $token): void
    {
        if (trim((string) $token) === '') {
            throw new NCMException('API token is required to communicate with Laravel News.');
        }
    }
}
