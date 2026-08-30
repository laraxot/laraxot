<?php

declare(strict_types=1);

namespace Modules\Platform\Providers\Filament;

use Filament\Panel;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

/**
 * Panel trasversale "operator": la superficie di lavoro del tecnico in campo.
 *
 * Portato dal gestionale sorgente (app/Providers/Filament/OperatorPanelProvider)
 * e riscritto su XotBasePanelProvider. Vive nel modulo Platform perché è una
 * superficie cross-modulo, non appartiene a un singolo dominio verticale.
 *
 * L'id 'operator' è semantico: pagine e widget (es. InterventionCalendar,
 * RecordInterventionReportPage) filtrano i dati "solo miei" quando il panel
 * corrente è questo, e AccountFeatures applica le feature operator.*.
 */
class OperatorPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Platform';

    protected ?string $panelId = 'operator';

    protected ?string $panelPath = 'operator';

    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel);

        foreach (['Customer', 'WorkOrder', 'Intervention', 'Catalog', 'Quotation', 'Vehicle', 'HR', 'Billing'] as $module) {
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
