<?php

declare(strict_types=1);

namespace Modules\Wts\Providers;

use Modules\Wts\Legacy\ConfiguredLegacyTechnicalSheetGateway;
use Modules\Wts\Legacy\LegacyTechnicalSheetGateway;
use Modules\Xot\Providers\XotBaseServiceProvider;

class WtsServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Wts';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        // XotBase espone config/wts.php come `wts.wts.*`: il codice del dominio
        // legge `config('wts.*')` come nel gestionale sorgente.
        $configPath = module_path($this->name, 'config/wts.php');

        if (is_file($configPath)) {
            $this->mergeConfigFrom($configPath, 'wts');
        }

        // La scheda tecnica arriva dal gestionale legacy: l'implementazione la
        // sceglie la configurazione, il dominio conosce solo il contratto.
        $this->app->bind(LegacyTechnicalSheetGateway::class, ConfiguredLegacyTechnicalSheetGateway::class);
    }
}
