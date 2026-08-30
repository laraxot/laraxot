<?php

declare(strict_types=1);

namespace Modules\MetalWork\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class MetalWorkServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'MetalWork';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        // XotBase registra config/metalwork.php sotto `metalwork.metalwork.*` (glob
        // della cartella config del modulo). Il codice del dominio legge
        // `config('metalwork.*')`, come nel gestionale sorgente: qui aggiungiamo
        // l'alias, stesso schema gia' usato da Catalog per `metel`.
        $configPath = module_path($this->name, 'config/metalwork.php');

        if (is_file($configPath)) {
            $this->mergeConfigFrom($configPath, 'metalwork');
        }
    }
}
