<?php

declare(strict_types=1);

namespace Modules\Employee\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

/**
 * Service Provider per il modulo Employee.
 *
 * Questo provider gestisce la registrazione e configurazione
 * del modulo Employee nell'applicazione Laravel.
 *
 * Estende XotBaseServiceProvider per garantire:
 * - Configurazione automatica del modulo
 * - Registrazione viste e traduzioni
 * - Integrazione con il sistema Laraxot/PTVX
 * - Pattern uniformi con altri moduli
 */
class EmployeeServiceProvider extends XotBaseServiceProvider
{
    /**
     * Nome del modulo.
     */
    public string $name = 'Employee';

    /**
     * Directory del provider.
     */
    protected string $module_dir = __DIR__;

    /**
     * Namespace del provider.
     */
    protected string $module_ns = __NAMESPACE__;
}
