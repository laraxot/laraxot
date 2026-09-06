<?php

declare(strict_types=1);

namespace Modules\Xot\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Str;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Modules\Xot\Actions\Panel\ApplyMetatagToPanelAction;
// Remove if not used elsewhere implicitly
use Modules\Xot\Datas\XotData;
use Webmozart\Assert\Assert;

abstract class XotBasePanelProvider extends PanelProvider
{
    protected string $module;

    protected bool $topNavigation = false;

    protected bool $globalSearch = false;

    protected bool $navigation = true;

    /**
     * Id/path del panel. Di default derivano dal modulo ({modulo}::admin,
     * {modulo}/admin); i panel trasversali (operator, customer, supplier)
     * li sovrascrivono perché Filament vieta di ri-assegnare l'id dopo
     * la prima chiamata a ->id().
     */
    protected ?string $panelId = null;

    protected ?string $panelPath = null;

    /**
     * Scoperta automatica di resource, pagine, widget e cluster del modulo.
     *
     * I panel per utenti esterni (customer, supplier) la spengono: montano a mano
     * la manciata di schermate che quell'utente deve vedere, e non devono
     * ritrovarsi dentro le resource amministrative del modulo che li ospita solo
     * perche' stanno nella stessa cartella.
     */
    protected bool $discoverModuleComponents = true;

    public function panel(Panel $panel): Panel
    {
        $moduleNamespace = $this->getModuleNamespace();
        $moduleLow = Str::lower($this->module);
        $mainModuleLow = Str::lower(XotData::make()->main_module); // Renamed to camelCase
        $default = $mainModuleLow === $moduleLow;

        $panel = $panel
            ->default($default)
            ->login() // UNCOMMENTED
            // ->registration()
            ->passwordReset()
            // ->emailVerification()
            // ->profile()
            ->sidebarFullyCollapsibleOnDesktop();

        $panel = app(ApplyMetatagToPanelAction::class)->execute(panel: $panel);
        // ---------------------
        $panel->maxContentWidth('full')
            ->topNavigation($this->topNavigation)
            ->globalSearch($this->globalSearch)
            ->readOnlyRelationManagersOnResourceViewPagesByDefault(false)
            ->navigation($this->navigation)
            // ->tenant($teamClass)
            // ->tenant($teamClass,ownershipRelationship:'users')
            // ->tenant($teamClass)
            ->id($this->panelId ?? $moduleLow.'::admin')
            ->path($this->panelPath ?? $moduleLow.'/admin')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

        if ($this->discoverModuleComponents) {
            $panel
                ->discoverResources(
                    base_path('Modules/'.$this->module.'/app/Filament/Resources'),
                    sprintf('%s\\Filament\\Resources', $moduleNamespace),
                )
                ->discoverPages(
                    base_path('Modules/'.$this->module.'/app/Filament/Pages'),
                    sprintf('%s\\Filament\\Pages', $moduleNamespace),
                )
                ->discoverWidgets(
                    base_path('Modules/'.$this->module.'/app/Filament/Widgets'),
                    sprintf('%s\\Filament\\Widgets', $moduleNamespace),
                )
                ->discoverClusters(
                    base_path('Modules/'.$this->module.'/app/Filament/Clusters'),
                    sprintf('%s\\Filament\\Clusters', $moduleNamespace),
                );
        }

        return $panel;
    }

    protected function getModuleNamespace(): string
    {
        Assert::string($ns = config('modules.namespace'));

        return $ns.'\\'.$this->module;
    }
}
