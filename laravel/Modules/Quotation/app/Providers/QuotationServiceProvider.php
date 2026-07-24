<?php

declare(strict_types=1);

namespace Modules\Quotation\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\Quotation\Console\Commands\BackfillConfirmedAtCommand;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationTemplate;
use Modules\Quotation\Policies\QuotationPolicy;
use Modules\Quotation\Policies\QuotationTemplatePolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

class QuotationServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Quotation';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        if ($this->app->runningInConsole()) {
            $this->commands([
                BackfillConfirmedAtCommand::class,
            ]);
        }
    }

    public function boot(): void
    {
        parent::boot();

        Gate::policy(Quotation::class, QuotationPolicy::class);
        Gate::policy(QuotationTemplate::class, QuotationTemplatePolicy::class);
    }
}
