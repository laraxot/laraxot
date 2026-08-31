<?php

declare(strict_types=1);

namespace Modules\Platform\Providers\Filament;

use Filament\Panel;
use Modules\Billing\Filament\Resources\InvoiceResource;
use Modules\Intervention\Filament\Pages\CustomerInterventionCalendar;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\Platform\Filament\Pages\Dashboard;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

/**
 * Panel trasversale "customer": quello che il cliente vede delle proprie commesse.
 *
 * Sta in Platform come l'operator panel: e' una superficie per un tipo di utente,
 * non un dominio. Monta solo cio' che al cliente serve davvero — le sue commesse,
 * le sue fatture, il calendario degli interventi — e niente altro: le resource
 * decidono poi cosa mostrare in base al panel corrente.
 */
class CustomerPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Platform';

    protected ?string $panelId = 'customer';

    protected ?string $panelPath = 'customer';

    /**
     * Qui non si scopre niente: il cliente (o il fornitore) vede solo le schermate
     * elencate sotto. Con la discovery attiva si ritroverebbe dentro le resource
     * amministrative del modulo Platform, audit log compreso.
     */
    protected bool $discoverModuleComponents = false;

    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel)
            ->resources([
                WorkOrderResource::class,
                InvoiceResource::class,
            ])
            ->pages([
                Dashboard::class,
                CustomerInterventionCalendar::class,
            ]);
    }
}
