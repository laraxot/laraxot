<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\Panel;
use Modules\Xot\Providers\Filament\XotBaseMainPanelProvider;

class AdminPanelProvider extends XotBaseMainPanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel);

        // Panel amministrativo aggregato (id 'admin'): come nel gestionale
        // sorgente espone le superfici operative dei moduli verticali, così le
        // pagine cross-panel (calendario interventi, rapportino) possono
        // costruire URL con panel 'admin'.
        foreach (['Customer', 'WorkOrder', 'Intervention', 'Catalog', 'Quotation', 'Vehicle', 'HR', 'Billing', 'Signage', 'MetalWork'] as $module) {
            $panel = $panel->discoverResources(
                base_path('Modules/'.$module.'/app/Filament/Resources'),
                sprintf('Modules\\%s\\Filament\\Resources', $module),
            );
        }

        foreach (['Signature', 'Document', 'Intervention', 'Quotation', 'Billing', 'WorkOrder'] as $module) {
            $panel = $panel->discoverPages(
                base_path('Modules/'.$module.'/app/Filament/Pages'),
                sprintf('Modules\\%s\\Filament\\Pages', $module),
            );
        }

        return $panel;
    }
}
