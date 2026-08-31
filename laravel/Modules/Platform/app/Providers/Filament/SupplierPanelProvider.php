<?php

declare(strict_types=1);

namespace Modules\Platform\Providers\Filament;

use Filament\Panel;
use Modules\Inventory\Filament\Resources\InventoryMovementResource;
use Modules\Inventory\Filament\Resources\WarehouseResource;
use Modules\Platform\Filament\Pages\Dashboard;
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

/**
 * Panel trasversale "supplier": magazzino e movimentazioni concordate col fornitore.
 *
 * Come il customer panel, vive in Platform perche' definisce una superficie per un
 * tipo di utente e non un dominio.
 */
class SupplierPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'Platform';

    protected ?string $panelId = 'supplier';

    protected ?string $panelPath = 'supplier';

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
                WarehouseResource::class,
                InventoryMovementResource::class,
            ])
            ->pages([
                Dashboard::class,
            ]);
    }
}
