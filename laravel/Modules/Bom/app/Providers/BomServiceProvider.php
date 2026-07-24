<?php

declare(strict_types=1);

namespace Modules\Bom\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\Bom\Models\Bom;
use Modules\Bom\Models\BomItem;
use Modules\Bom\Policies\BomItemPolicy;
use Modules\Bom\Policies\BomPolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

class BomServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Bom';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();

        Gate::policy(Bom::class, BomPolicy::class);
        Gate::policy(BomItem::class, BomItemPolicy::class);
    }
}
