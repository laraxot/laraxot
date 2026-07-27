<?php

declare(strict_types=1);

namespace Modules\Geo\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class GeoServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Geo';
    protected string $moduleName = 'Geo';
    protected string $namespace = 'geo';

    public function boot(): void
    {
        parent::boot();

        // $this->registerMapAssets();
    }
}
