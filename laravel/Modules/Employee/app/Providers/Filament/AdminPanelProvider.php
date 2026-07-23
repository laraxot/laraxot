<?php

declare(strict_types=1);

namespace Modules\Employee\Providers\Filament;

use Filament\Navigation\MenuItem;
use Filament\Panel;
use Modules\Employee\Filament\Pages\Dashboard;
use Modules\Employee\Filament\Resources\WorkHourResource;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;
use Override;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Employee';

    #[Override]
    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel);

        // Configurazioni specifiche del modulo Employee
        $panel
            ->login()
            ->pages([
                Dashboard::class,
            ])
            ->resources([
                WorkHourResource::class,
            ]);

        // Menu items specifici - temporaneamente disabilitati per evitare binding issues
        // TODO: Riabilitare quando il problema di binding sarà risolto
        /*
        try {
            $dashboardUrl = Dashboard::getUrl(panel: $panel->getId());
        } catch (\Exception $e) {
            // Fallback sicuro per evitare errori di binding
            $dashboardUrl = '/employee/admin';
        }

        $panel->userMenuItems([
            MenuItem::make()
                ->label('Gestione Dipendenti')
                ->url($dashboardUrl)
                ->icon('heroicon-m-users'),
        ]);
        */

        return $panel;
    }
}
