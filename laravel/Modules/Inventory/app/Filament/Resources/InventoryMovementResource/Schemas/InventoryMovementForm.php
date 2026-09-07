<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\InventoryMovementResource\Schemas;

use Carbon\CarbonInterface;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Modules\Catalog\Models\Product;
use Modules\Intervention\Models\Intervention;
use Modules\Inventory\Actions\GetStockSnapshotAction;
use Modules\Inventory\Filament\Resources\InventoryMovementResource;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseLocation;
use Modules\Platform\Models\PlatformSetting;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Actions\Cast\SafeIntCastAction;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class InventoryMovementForm extends XotBaseResourceForm
{
    protected static string $resource = InventoryMovementResource::class;

    /**
     * @return array<string, \Filament\Schemas\Components\Component>
     */
    public function getFormSchema(): array
    {
        $storageZonesEnabled = self::storageZonesEnabled();
        $hasMovementType = fn (callable $get): bool => trim(SafeStringCastAction::cast($get('movement_type'))) !== '';

        return [
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        Select::make('movement_type')
                            ->label(__('inventory.fields.movement_type'))
                            ->options([
                                'carico' => __('inventory.movement_types.carico'),
                                'scarico' => __('inventory.movement_types.scarico'),
                                'trasferimento' => __('inventory.movement_types.trasferimento'),
                                'reso' => __('inventory.movement_types.reso'),
                            ])
                            ->live()
                            ->afterStateUpdated(function (callable $set, ?string $state): void {
                                $type = (string) ($state ?? '');
                                if ($type === 'carico') {
                                    $set('from_warehouse_id', null);
                                    $set('from_location_id', null);
                                } elseif ($type === 'scarico') {
                                    $set('to_warehouse_id', null);
                                    $set('to_location_id', null);
                                }

                                $set('product_id', null);
                            })
                            ->required()
                            ->helperText('Scegli prima il tipo di movimento: il resto del form si adatta in base a questa scelta.'),
                    ]),
                    Grid::make(2)->components([
                        Select::make('product_id')
                            ->label(__('inventory.fields.product'))
                            ->options(fn (callable $get): array => self::productOptionsForMovement(
                                SafeStringCastAction::cast($get('movement_type')),
                                SafeIntCastAction::cast($get('from_warehouse_id')),
                            ))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->visible($hasMovementType)
                            ->required($hasMovementType),
                    ]),
                    Grid::make(3)->components([
                        TextInput::make('quantity')
                            ->label(__('inventory.fields.quantity'))
                            ->numeric()
                            ->visible($hasMovementType)
                            ->required($hasMovementType),
                        TextInput::make('unit_price')
                            ->label(__('inventory.fields.unit_price'))
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('EUR')
                            ->visible($hasMovementType),
                        Select::make('from_warehouse_id')
                            ->label(__('inventory.fields.from_warehouse'))
                            ->options(fn () => Warehouse::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (callable $set, callable $get): void {
                                $set('from_location_id', null);

                                if (in_array(SafeStringCastAction::cast($get('movement_type')), ['scarico', 'trasferimento', 'reso'], true)) {
                                    $set('product_id', null);
                                }
                            })
                            ->visible(fn (callable $get): bool => in_array(SafeStringCastAction::cast($get('movement_type')), ['scarico', 'trasferimento', 'reso'], true))
                            ->required(fn (callable $get): bool => in_array(SafeStringCastAction::cast($get('movement_type')), ['scarico', 'trasferimento', 'reso'], true))
                            ->dehydrateStateUsing(fn ($state, callable $get) => in_array(SafeStringCastAction::cast($get('movement_type')), ['scarico', 'trasferimento', 'reso'], true) ? $state : null),
                        Select::make('from_location_id')
                            ->label(__('inventory.fields.from_location'))
                            ->options(fn (callable $get): array => self::locationOptionsForWarehouse(SafeIntCastAction::cast($get('from_warehouse_id'))))
                            ->searchable()
                            ->preload()
                            ->visible(fn (callable $get): bool => $storageZonesEnabled
                                && in_array(SafeStringCastAction::cast($get('movement_type')), ['scarico', 'trasferimento', 'reso'], true)
                                && SafeIntCastAction::cast($get('from_warehouse_id')) > 0)
                            ->dehydrateStateUsing(fn ($state, callable $get) => $storageZonesEnabled
                                && in_array(SafeStringCastAction::cast($get('movement_type')), ['scarico', 'trasferimento', 'reso'], true)
                                ? $state
                                : null),
                        Select::make('to_warehouse_id')
                            ->label(__('inventory.fields.to_warehouse'))
                            ->options(fn () => Warehouse::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (callable $set): mixed => $set('to_location_id', null))
                            ->visible(fn (callable $get): bool => in_array(SafeStringCastAction::cast($get('movement_type')), ['carico', 'trasferimento', 'reso'], true))
                            ->required(fn (callable $get): bool => in_array(SafeStringCastAction::cast($get('movement_type')), ['carico', 'trasferimento', 'reso'], true))
                            ->dehydrateStateUsing(fn ($state, callable $get) => in_array(SafeStringCastAction::cast($get('movement_type')), ['carico', 'trasferimento', 'reso'], true) ? $state : null),
                        Select::make('to_location_id')
                            ->label(__('inventory.fields.to_location'))
                            ->options(fn (callable $get): array => self::locationOptionsForWarehouse(SafeIntCastAction::cast($get('to_warehouse_id'))))
                            ->searchable()
                            ->preload()
                            ->visible(fn (callable $get): bool => $storageZonesEnabled
                                && in_array(SafeStringCastAction::cast($get('movement_type')), ['carico', 'trasferimento', 'reso'], true)
                                && SafeIntCastAction::cast($get('to_warehouse_id')) > 0)
                            ->dehydrateStateUsing(fn ($state, callable $get) => $storageZonesEnabled
                                && in_array(SafeStringCastAction::cast($get('movement_type')), ['carico', 'trasferimento', 'reso'], true)
                                ? $state
                                : null),
                    ]),
                    TextEntry::make('source_stock_hint')
                        ->label(__('inventory.fields.available_stock'))
                        ->state(fn (callable $get): string => self::sourceStockHint(
                            SafeStringCastAction::cast($get('movement_type')),
                            SafeIntCastAction::cast($get('product_id')),
                            SafeIntCastAction::cast($get('from_warehouse_id')),
                            SafeIntCastAction::cast($get('from_location_id')),
                        ))
                        ->visible(fn (callable $get): bool => in_array(SafeStringCastAction::cast($get('movement_type')), ['scarico', 'trasferimento', 'reso'], true)),
                    Grid::make(2)->components([
                        Select::make('work_order_id')
                            ->label(__('inventory.fields.work_order'))
                            ->options(fn (): array => self::workOrderOptions())
                            ->searchable()
                            ->preload()
                            ->visible($hasMovementType),
                        Select::make('intervention_id')
                            ->label(__('inventory.fields.intervention'))
                            ->options(fn (): array => self::interventionOptions())
                            ->searchable()
                            ->preload()
                            ->visible($hasMovementType),
                    ]),
                    DateTimePicker::make('occurred_at')
                        ->label(__('inventory.fields.occurred_at'))
                        ->default(fn (): CarbonInterface => Carbon::now())
                        ->visible($hasMovementType),
                    Textarea::make('note')
                        ->label(__('inventory.fields.note'))
                        ->visible($hasMovementType)
                        ->columnSpanFull(),
                ]),
        ];
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

    /**
     * Usato anche da InventoryMovementsTable per decidere le colonne condizionali
     * fromLocation/toLocation, quindi resta public.
     */
    public static function storageZonesEnabled(): bool
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

    /**
     * Usato anche da InventoryMovementsTable per la colonna workOrder.code, quindi resta public.
     */
    public static function workOrderLabel(?WorkOrder $workOrder): string
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

    /**
     * Usato anche da InventoryMovementsTable per la colonna intervention_id, quindi resta public.
     */
    public static function interventionLabel(?Intervention $intervention): string
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
