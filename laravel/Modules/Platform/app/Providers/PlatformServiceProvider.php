<?php

declare(strict_types=1);

namespace Modules\Platform\Providers;

use Modules\Billing\Infrastructure\Models\Invoice;
use Modules\Customer\Infrastructure\Models\Customer;
use Modules\Intervention\Infrastructure\Models\Intervention;
use Modules\Platform\Models\PlatformSetting;
use Modules\Platform\Observers\AuditObserver;
use Modules\Platform\Settings\SettingsRepository;
use Modules\Quotation\Infrastructure\Models\Quotation;
use Modules\WorkOrder\Infrastructure\Models\WorkOrder;
use Modules\Xot\Providers\XotBaseServiceProvider;

/**
 * NOTA (TODO esterno): i modelli Billing\Invoice, Customer\Customer, Intervention\Intervention,
 * Quotation\Quotation e WorkOrder\WorkOrder sono referenziati ancora al percorso
 * Infrastructure\Models perché quei moduli non sono stati ancora reshape-ati in questa wave.
 */
class PlatformServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Platform';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        $this->app->singleton(SettingsRepository::class, fn () => new SettingsRepository());
    }

    public function boot(): void
    {
        parent::boot();

        WorkOrder::observe(AuditObserver::class);
        Intervention::observe(AuditObserver::class);
        Customer::observe(AuditObserver::class);
        Invoice::observe(AuditObserver::class);
        Quotation::observe(AuditObserver::class);
        PlatformSetting::observe(AuditObserver::class);
    }
}
