<?php

declare(strict_types=1);

namespace Modules\Catalog\Providers;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\Gate;
use Modules\Catalog\Models\CatalogFrequency;
use Modules\Catalog\Models\CatalogMeasureUnit;
use Modules\Catalog\Models\CatalogModel;
use Modules\Catalog\Models\CatalogPartType;
use Modules\Catalog\Models\Product;
use Modules\Catalog\Policies\CatalogFrequencyPolicy;
use Modules\Catalog\Policies\CatalogMeasureUnitPolicy;
use Modules\Catalog\Policies\CatalogModelPolicy;
use Modules\Catalog\Policies\CatalogPartTypePolicy;
use Modules\Catalog\Policies\ProductPolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

class CatalogServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Catalog';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        // XotBase registra config/metel.php come catalog.metel.*; Actions/test SRC
        // usano config('metel.*') come nel gestionale (root config/metel.php).
        $metelPath = module_path($this->name, 'config/metel.php');
        if (is_file($metelPath)) {
            $this->mergeConfigFrom($metelPath, 'metel');
        }
    }

    public function boot(): void
    {
        parent::boot();

        $this->applyMetelEnvOverrides();

        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(CatalogFrequency::class, CatalogFrequencyPolicy::class);
        Gate::policy(CatalogMeasureUnit::class, CatalogMeasureUnitPolicy::class);
        Gate::policy(CatalogPartType::class, CatalogPartTypePolicy::class);
        Gate::policy(CatalogModel::class, CatalogModelPolicy::class);
    }

    /**
     * Module config cannot call env() (Larastan). Overlay METEL_* from runtime env here.
     */
    private function applyMetelEnvOverrides(): void
    {
        $enabledRaw = Env::get('METEL_API_ENABLED');
        $timeoutRaw = Env::get('METEL_API_TIMEOUT');
        $baseUrlRaw = Env::get('METEL_API_BASE_URL') ?? config('metel.api.base_url');
        $apiKeyRaw = Env::get('METEL_API_KEY') ?? config('metel.api.api_key');
        $articlesEndpointRaw = Env::get('METEL_API_ARTICLES_ENDPOINT') ?? config('metel.api.articles_endpoint');
        $itemsPathRaw = Env::get('METEL_API_ITEMS_PATH') ?? config('metel.api.items_path');

        config([
            'metel.api.enabled' => filter_var($enabledRaw ?? '0', FILTER_VALIDATE_BOOL),
            'metel.api.base_url' => is_string($baseUrlRaw) ? $baseUrlRaw : '',
            'metel.api.api_key' => is_string($apiKeyRaw) ? $apiKeyRaw : '',
            'metel.api.articles_endpoint' => is_string($articlesEndpointRaw) ? $articlesEndpointRaw : '',
            'metel.api.items_path' => is_string($itemsPathRaw) ? $itemsPathRaw : '',
            'metel.api.timeout' => is_numeric($timeoutRaw) ? (int) $timeoutRaw : (int) config('metel.api.timeout', 60),
        ]);
    }
}
