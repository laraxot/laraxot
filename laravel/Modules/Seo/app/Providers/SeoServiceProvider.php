<?php

declare(strict_types=1);

namespace Modules\Seo\Providers;

use Modules\Seo\Adapters\MetatagFacadeAdapter;
use Modules\Seo\Adapters\MetatagManager;
use Modules\Seo\Adapters\MetatagState;
use Modules\Xot\Providers\XotBaseServiceProvider;

class SeoServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Seo';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->app->singleton(MetatagState::class);
        $this->app->singleton(MetatagFacadeAdapter::class);

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
            MetatagFacadeAdapter::class,
            MetatagState::class,
            MetatagManager::class,
        ];
    }
}
