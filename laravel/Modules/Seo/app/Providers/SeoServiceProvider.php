<?php

declare(strict_types=1);

namespace Modules\Seo\Providers;

use Modules\Seo\Adapters\MetatagManager;
use Modules\Xot\Providers\XotBaseServiceProvider;

class SeoServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Seo';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->app->singleton(MetatagManager::class, function () {
            return new MetatagManager();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            MetatagManager::class,
        ];
    }
}
