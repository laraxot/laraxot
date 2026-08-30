<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Providers\Filament;

use Filament\Panel;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'WorkOrder';

    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel);

        // Gli interventi sono l'esecuzione degli ordini di lavoro: sul panel
        // WorkOrder (default dell'app) devono essere raggiungibili le resource
        // e le pagine di Intervention (calendario, rapportino, dettaglio).
        return $panel
            ->discoverResources(
                base_path('Modules/Intervention/app/Filament/Resources'),
                'Modules\\Intervention\\Filament\\Resources',
            )
            ->discoverPages(
                base_path('Modules/Intervention/app/Filament/Pages'),
                'Modules\\Intervention\\Filament\\Pages',
            );
    }
}
