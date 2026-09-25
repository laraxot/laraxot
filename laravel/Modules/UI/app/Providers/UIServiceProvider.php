<?php

declare(strict_types=1);

namespace Modules\UI\Providers;

use Modules\UI\Adapters\Map\NullGeocodingServiceAdapter;
use Modules\UI\Adapters\Map\NullMapServiceAdapter;
use Modules\UI\Contracts\GeocodingServiceContract;
use Modules\UI\Contracts\MapServiceContract;
use Modules\Xot\Actions\Module\GetModulePathByGeneratorAction;
use Modules\Xot\Providers\XotBaseServiceProvider;

/**
 * Service Provider per il modulo UI.
 *
 * Nota: la registrazione dei Blade components modulari avviene tramite GetModulePathByGeneratorAction
 * per garantire la corretta risoluzione dei path secondo la struttura dei moduli.
 *
 * @phpstan-type ModuleConfig array{name: string, alias: string, description: string, keywords: array<int, string>, priority: int, providers: array<int, class-string>}
 */
class UIServiceProvider extends XotBaseServiceProvider
{
    /**
     * Nome del modulo.
     */
    public string $name = 'UI';

    /**
     * Directory del modulo.
     */
    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function register(): void
    {
        parent::register();

        $this->app->singleton(MapServiceContract::class, NullMapServiceAdapter::class);
        $this->app->singleton(GeocodingServiceContract::class, NullGeocodingServiceAdapter::class);
    }

    /**
     * Boot del service provider.
     *
     * Configura i componenti Blade e altre funzionalità del modulo UI.
     *
     * @return void
     */

    /**
     * Registra i servizi del provider.
     *
     * @return void
     */

    /**
     * Restituisce il percorso delle viste dei componenti UI.
     */
    public function getComponentViewPath(): string
    {
        return app(GetModulePathByGeneratorAction::class)->execute($this->name, 'component-view');
    }
}
