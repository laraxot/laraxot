<?php

declare(strict_types=1);

namespace Modules\Xot\Providers\Filament;

use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Modules\Xot\Filament\Pages\MainDashboard;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Xot';

    /**
     * Register pages for the Xot admin panel.
     */
    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel);

        // Ensure a dashboard page exists for this panel so the topbar home link works.
        $panel = $panel->pages([
            MainDashboard::class,
        ]);

        // Story xot-artisan-commands-manager-layout-and-composer-dump-autoload.md:
        // il pannello "xot" non ha un tema Vite proprio, quindi il contenitore
        // delle azioni header di Filament (.fi-ac) non va a capo di default —
        // con molte azioni su ArtisanCommandsManager, quelle in eccesso
        // escono dallo schermo invece di scendere su una riga successiva.
        FilamentAsset::register(
            [
                Css::make('xot-header-actions-wrap', asset('assets/xot/header-actions-wrap.css')),
            ],
            'xot::admin',
        );

        return $panel;
    }
}
