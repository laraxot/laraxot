<?php

declare(strict_types=1);

namespace Modules\Catalog\Providers;

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

    public function boot(): void
    {
        parent::boot();

        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(CatalogFrequency::class, CatalogFrequencyPolicy::class);
        Gate::policy(CatalogMeasureUnit::class, CatalogMeasureUnitPolicy::class);
        Gate::policy(CatalogPartType::class, CatalogPartTypePolicy::class);
        Gate::policy(CatalogModel::class, CatalogModelPolicy::class);
    }
}
