<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources;

use App\Support\AccountFeatures;
use Carbon\CarbonInterface;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Catalog\Models\Product;
use Modules\Intervention\Models\Intervention;
use Modules\Inventory\Actions\GetStockSnapshotAction;
use Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages\CreateInventoryMovement;
use Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages\EditInventoryMovement;
use Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages\ListInventoryMovements;
use Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages\ViewInventoryMovement;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseLocation;
use Modules\Platform\Models\PlatformSetting;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Actions\Cast\SafeIntCastAction;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class InventoryMovementResource extends XotBaseResource
{
    protected static ?string $model = InventoryMovement::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function canView(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    public static function getNavigationGroup(): string
    {
        return __('inventory.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('inventory.movement');
    }

    public static function getPluralModelLabel(): string
    {
        return __('inventory.movements');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventoryMovements::route('/'),
            'create' => CreateInventoryMovement::route('/create'),
            'view' => ViewInventoryMovement::route('/{record}'),
            'edit' => EditInventoryMovement::route('/{record}/edit'),
        ];
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.inventory',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }

    /**
     * @return array<int, string>
     */
    private static function locationOptionsForWarehouse(int $warehouseId): array
    {
        if ($warehouseId <= 0) {
            return [];
        }

        $options = [];
        foreach (
            WarehouseLocation::query()
                ->where('warehouse_id', $warehouseId)
                ->where('is_active', true)
                ->orderBy('code')
                ->get(['id', 'code']) as $location
        ) {
            $options[$location->id] = $location->code;
        }

        return $options;
    }

    private static function storageZonesEnabled(): bool
    {
        return PlatformSetting::getBool('inventory.enable_storage_zones', false);
    }

    /**
     * @return array<int, string>
     */
    private static function productOptionsForMovement(string $movementType, int $fromWarehouseId): array
    {
        $requiresSourceStock = in_array($movementType, ['scarico', 'trasferimento', 'reso'], true);

        if (! $requiresSourceStock || $fromWarehouseId <= 0) {
            $options = [];
            foreach (
                Product::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->get(['id', 'name']) as $product
            ) {
                $options[SafeIntCastAction::cast($product->getAttribute('id'))] = SafeStringCastAction::cast($product->getAttribute('name'));
            }

            return $options;
        }

        $stockRows = self::stockSummaryByWarehouse($fromWarehouseId);
        if ($stockRows === []) {
            return [];
        }

        /** @var Collection<int, Product> $products */
        $products = Product::query()
            ->whereIn('id', array_keys($stockRows))
            ->orderBy('name')
            ->get(['id', 'name', 'sku']);

        $options = [];
        foreach ($products as $product) {
            $productId = SafeIntCastAction::cast($product->getAttribute('id'));
            $stock = $stockRows[$productId] ?? 0;
            $sku = $product->getAttribute('sku');
            $name = $product->getAttribute('name');
            $label = trim(implode(' - ', array_filter([
                is_scalar($sku) ? (string) $sku : null,
                is_scalar($name) ? (string) $name : null,
            ])));

            $options[$productId] = sprintf('%s (%s: %d)', $label, __('inventory.fields.available_stock'), $stock);
        }

        return $options;
    }

    /**
     * @return array<int, int>
     */
    private static function stockSummaryByWarehouse(int $warehouseId): array
    {
        $rows = Product::query()
            ->join('inventory_movements', 'inventory_movements.product_id', '=', 'products.id')
            ->where(function ($query) use ($warehouseId): void {
                $query->where('inventory_movements.to_warehouse_id', $warehouseId)
                    ->orWhere('inventory_movements.from_warehouse_id', $warehouseId);
            })
            ->groupBy('products.id')
            ->selectRaw(
                'products.id as product_id,
                 SUM(CASE WHEN inventory_movements.to_warehouse_id = ? THEN inventory_movements.quantity ELSE 0 END)
                 - SUM(CASE WHEN inventory_movements.from_warehouse_id = ? THEN inventory_movements.quantity ELSE 0 END) as available_stock',
                [$warehouseId, $warehouseId],
            )
            ->havingRaw('available_stock > 0')
            ->pluck('available_stock', 'product_id')
            ->all();

        $result = [];
        foreach ($rows as $productId => $value) {
            $result[SafeIntCastAction::cast($productId)] = SafeIntCastAction::cast($value);
        }

        return $result;
    }

    private static function sourceStockHint(string $movementType, int $productId, int $warehouseId, int $locationId): string
    {
        if (! in_array($movementType, ['scarico', 'trasferimento', 'reso'], true) || $productId <= 0 || $warehouseId <= 0) {
            return 'Seleziona magazzino sorgente e prodotto per vedere la disponibilità.';
        }

        $snapshot = app(GetStockSnapshotAction::class)->execute(
            $productId,
            $warehouseId,
            $locationId > 0 ? $locationId : null,
        );

        if ($locationId > 0) {
            return sprintf('%d disponibili nell\'ubicazione sorgente.', (int) $snapshot['aggregate']);
        }

        return sprintf('%d disponibili nel magazzino sorgente.', (int) $snapshot['aggregate']);
    }

    /**
     * @return array<int, string>
     */
    private static function workOrderOptions(): array
    {
        /** @var Collection<int, WorkOrder> $workOrders */
        $workOrders = WorkOrder::query()
            ->with(['customer', 'site'])
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $options = [];
        foreach ($workOrders as $workOrder) {
            $options[(int) $workOrder->id] = self::workOrderLabel($workOrder);
        }

        return $options;
    }

    /**
     * @return array<int, string>
     */
    private static function interventionOptions(): array
    {
        /** @var Collection<int, Intervention> $interventions */
        $interventions = Intervention::query()
            ->with(['workOrder.customer', 'workOrder.site'])
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $options = [];
        foreach ($interventions as $intervention) {
            $options[(int) $intervention->id] = self::interventionLabel($intervention);
        }

        return $options;
    }

    private static function workOrderLabel(?WorkOrder $workOrder): string
    {
        if (! $workOrder instanceof WorkOrder) {
            return '—';
        }

        $parts = array_filter([
            $workOrder->code,
            $workOrder->title,
            $workOrder->customer?->legal_name,
        ]);

        $label = implode(' - ', array_map(static fn ($value): string => (string) $value, $parts));

        if ($workOrder->site?->name !== null && trim((string) $workOrder->site->name) !== '') {
            $label .= ' ('.$workOrder->site->name.')';
        }

        return $label !== '' ? $label : ('Commessa #'.$workOrder->id);
    }

    private static function interventionLabel(?Intervention $intervention): string
    {
        if (! $intervention instanceof Intervention) {
            return '—';
        }

        $parts = [
            '#'.$intervention->id,
            $intervention->workOrder?->customer?->legal_name,
        ];

        if ($intervention->workOrder?->site?->name !== null && trim((string) $intervention->workOrder->site->name) !== '') {
            $parts[] = $intervention->workOrder->site->name;
        }

        $workOrder = $intervention->workOrder;
        $title = $workOrder instanceof WorkOrder ? ($workOrder->title ?? $workOrder->code) : null;
        if (is_string($title) && trim($title) !== '') {
            $parts[] = $title;
        }

        if ($intervention->scheduled_at !== null) {
            $parts[] = $intervention->scheduled_at->format('d/m/Y H:i');
        }

        return implode(' - ', array_filter($parts, static fn ($value): bool => is_string($value) && trim($value) !== ''));
    }
}
