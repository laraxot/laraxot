<?php

declare(strict_types=1);

namespace Modules\HR\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\HR\Models\AbsenceRequest;
use Modules\HR\Models\Employee;
use Modules\HR\Policies\AbsenceRequestPolicy;
use Modules\HR\Policies\EmployeePolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

/**
 * Service Provider per il modulo HR.
 *
 * Estende XotBaseServiceProvider per garantire config/views/translations/migrations/comandi
 * automatici; qui vengono mantenute solo le policy specifiche del modulo.
 */
class HrServiceProvider extends XotBaseServiceProvider
{
    /**
     * Nome del modulo.
     */
    public string $name = 'HR';

    /**
     * Directory del provider.
     */
    protected string $module_dir = __DIR__;

    /**
     * Namespace del provider.
     */
    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();

        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(AbsenceRequest::class, AbsenceRequestPolicy::class);
    }
}
