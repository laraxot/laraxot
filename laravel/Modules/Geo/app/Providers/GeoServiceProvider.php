<?php

declare(strict_types=1);

namespace Modules\Geo\Providers;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Modules\Xot\Providers\XotBaseServiceProvider;

use function Safe\file_get_contents;
use function Safe\json_decode;

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
