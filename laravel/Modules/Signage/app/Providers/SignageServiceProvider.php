<?php

declare(strict_types=1);

namespace Modules\Signage\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class SignageServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Signage';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        // XotBase espone config/signage.php come `signage.signage.*`: il codice del
        // dominio legge `config('signage.*')` come nel gestionale sorgente.
        $configPath = module_path($this->name, 'config/signage.php');

        if (is_file($configPath)) {
            $this->mergeConfigFrom($configPath, 'signage');
        }
    }
}
