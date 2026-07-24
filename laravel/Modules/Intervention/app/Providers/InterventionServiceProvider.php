<?php

declare(strict_types=1);

namespace Modules\Intervention\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Modules\Intervention\Console\Commands\SyncGoogleCalendarsCommand;
use Modules\Intervention\Contracts\ReportPaymentGatewayContract;
use Modules\Intervention\GoogleCalendar\Contracts\GoogleCalendarClient;
use Modules\Intervention\GoogleCalendar\GoogleApiCalendarClient;
use Modules\Intervention\Listeners\RestoreInterventionsForWorkOrderListener;
use Modules\Intervention\Models\DailyNote;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\Intervention\Observers\InterventionGoogleCalendarObserver;
use Modules\Intervention\Observers\InterventionMaterialLineObserver;
use Modules\Intervention\Payments\CashierReportPaymentGateway;
use Modules\Intervention\Policies\DailyNotePolicy;
use Modules\Intervention\Policies\InterventionPolicy;
use Modules\WorkOrder\Domain\Events\WorkOrderRestored;
use Modules\Xot\Providers\XotBaseServiceProvider;

class InterventionServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Intervention';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        $this->app->bind(ReportPaymentGatewayContract::class, CashierReportPaymentGateway::class);
        $this->app->bind(GoogleCalendarClient::class, GoogleApiCalendarClient::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncGoogleCalendarsCommand::class,
            ]);
        }
    }

    public function boot(): void
    {
        parent::boot();

        Gate::policy(Intervention::class, InterventionPolicy::class);
        Gate::policy(DailyNote::class, DailyNotePolicy::class);

        Event::listen(WorkOrderRestored::class, RestoreInterventionsForWorkOrderListener::class);

        Intervention::observe(InterventionGoogleCalendarObserver::class);

        // L'extra preventivo si ricalcola da se' appena cambiano i materiali:
        // e' l'unico modo perche' resti vero da qualunque punto lo si scriva.
        InterventionMaterialLine::observe(InterventionMaterialLineObserver::class);
    }
}
