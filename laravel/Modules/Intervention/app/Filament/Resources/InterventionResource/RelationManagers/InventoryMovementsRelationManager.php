<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Modules\Catalog\Models\Product;
use Modules\Intervention\Models\Intervention;
use Modules\Inventory\Actions\CreateInventoryMovementAction;
use Modules\Inventory\Actions\GetStockSnapshotAction;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseLocation;
use Modules\Platform\Settings\SettingsRepository;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class InventoryMovementsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'inventoryMovements';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Materiali impiegati';
    }

    private static function isEnabled(): bool
    {
        return app(SettingsRepository::class)->getBool('inventory.allow_link_interventions', true);
    }

    private static function storageZonesEnabled(): bool
    {
        return app(SettingsRepository::class)->getBool('inventory.enable_storage_zones', false);
    }

    public function getFormSchema(): array
    {
        return [
            Select::make('product_id')
                ->label(__('inventory.fields.product'))
                ->options(fn (): array => Product::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all())
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(function (callable $set, mixed $state): void {
                    $stateValue = $state ?? 0;
                    $productId = (int) (is_numeric($stateValue) ? $stateValue : 0);
                    $rawUnitPrice = $productId > 0
                        ? Product::query()->whereKey($productId)->value('unit_price')
                        : null;

                    $set('unit_price', is_numeric($rawUnitPrice) ? number_format((float) $rawUnitPrice, 2, '.', '') : '0.00');
                })
                ->required(),
            Select::make('from_warehouse_id')
                ->label(__('inventory.fields.from_warehouse'))
                ->options(fn (): array => Warehouse::query()
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all())
                ->searchable()
                ->preload()
                ->live()
                ->afterStateUpdated(fn (callable $set): mixed => $set('from_location_id', null))
                ->required(),
            Select::make('from_location_id')
                ->label(__('inventory.fields.from_location'))
                ->options(fn (callable $get): array => self::locationOptionsForWarehouse((int) (is_numeric($get('from_warehouse_id')) ? $get('from_warehouse_id') : 0)))
                ->searchable()
                ->preload()
                ->live()
                ->visible(fn (callable $get): bool => self::storageZonesEnabled() && (int) (is_numeric($get('from_warehouse_id')) ? $get('from_warehouse_id') : 0) > 0),
            TextInput::make('quantity')
                ->label(__('inventory.fields.quantity'))
                ->numeric()
                ->minValue(1)
                ->default(1)
                ->required(),
            TextInput::make('unit_price')
                ->label(__('inventory.fields.unit_price'))
                ->numeric()
                ->minValue(0)
                ->step(0.01)
                ->prefix('EUR')
                ->default('0.00')
                ->required(),
            Placeholder::make('stock_available')
                ->label(__('inventory.fields.available_stock'))
                ->content(function (callable $get): string {
                    $productIdValue = $get('product_id') ?? 0;
                    $productId = (int) (is_numeric($productIdValue) ? $productIdValue : 0);
                    $warehouseIdValue = $get('from_warehouse_id') ?? 0;
                    $warehouseId = (int) (is_numeric($warehouseIdValue) ? $warehouseIdValue : 0);
                    $locationIdValue = $get('from_location_id') ?? 0;
                    $locationId = (int) (is_numeric($locationIdValue) ? $locationIdValue : 0);

                    if ($productId <= 0 || $warehouseId <= 0) {
                        return 'Seleziona prodotto e magazzino per vedere la giacenza.';
                    }

                    $snapshot = app(GetStockSnapshotAction::class)->execute(
                        $productId,
                        $warehouseId,
                        $locationId > 0 ? $locationId : null,
                    );

                    if ($locationId > 0) {
                        return sprintf('%d disponibili in questa ubicazione.', (int) ($snapshot['aggregate'] ?? 0));
                    }

                    return sprintf('%d disponibili in questo magazzino.', (int) ($snapshot['aggregate'] ?? 0));
                }),
            Textarea::make('note')
                ->label(__('inventory.fields.note'))
                ->rows(3)
                ->placeholder('Es. n.1 timer scale, n.2 lampade LED, morsetti quadro...')
                ->columnSpanFull(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('movement_type', 'scarico')->latest('occurred_at'))
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('inventory.fields.product'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fromWarehouse.name')
                    ->label(__('inventory.fields.from_warehouse'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('fromLocation.code')
                    ->label(__('inventory.fields.from_location'))
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('inventory.fields.quantity'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label(__('inventory.fields.unit_price'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('line_total')
                    ->label(__('inventory.fields.line_total'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->label(__('inventory.fields.note'))
                    ->limit(90)
                    ->wrap(),
                Tables\Columns\TextColumn::make('occurred_at')
                    ->label(__('inventory.fields.occurred_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Aggiungi materiale')
                    ->visible(fn (): bool => self::isEnabled())
                    ->using(function (array $data): InventoryMovement {
                        if (! self::isEnabled()) {
                            throw ValidationException::withMessages([
                                'product_id' => 'La gestione materiali da magazzino su interventi è disabilitata nelle impostazioni.',
                            ]);
                        }

                        /** @var Intervention $intervention */
                        $intervention = $this->getOwnerRecord();

                        $productIdValue = $data['product_id'] ?? 0;
                        $productId = (int) (is_numeric($productIdValue) ? $productIdValue : 0);
                        $warehouseIdValue = $data['from_warehouse_id'] ?? 0;
                        $warehouseId = (int) (is_numeric($warehouseIdValue) ? $warehouseIdValue : 0);
                        $locationIdValue = $data['from_location_id'] ?? 0;
                        $locationId = (int) (is_numeric($locationIdValue) ? $locationIdValue : 0);
                        $qtyValue = $data['quantity'] ?? 0;
                        $quantity = (int) (is_numeric($qtyValue) ? $qtyValue : 0);
                        $priceValue = $data['unit_price'] ?? 0;
                        $unitPrice = round((float) (is_numeric($priceValue) ? $priceValue : 0), 2);

                        if ($productId <= 0 || $warehouseId <= 0 || $quantity <= 0) {
                            throw ValidationException::withMessages([
                                'product_id' => 'Completa prodotto, magazzino e quantità.',
                            ]);
                        }

                        self::assertLocationBelongsToWarehouse($warehouseId, $locationId, 'from_location_id');

                        $snapshot = app(GetStockSnapshotAction::class)->execute(
                            $productId,
                            $warehouseId,
                            $locationId > 0 ? $locationId : null,
                        );
                        $available = (int) $snapshot['aggregate'];

                        if ($available < $quantity) {
                            throw ValidationException::withMessages([
                                'quantity' => "Giacenza insufficiente: disponibili {$available} pezzi nel magazzino selezionato.",
                            ]);
                        }

                        try {
                            $noteValue = $data['note'] ?? '';
                            $note = is_string($noteValue) ? $noteValue : strval($noteValue);

                            return app(CreateInventoryMovementAction::class)->execute(
                                productId: $productId,
                                movementType: 'scarico',
                                quantity: $quantity,
                                fromWarehouseId: $warehouseId,
                                toWarehouseId: null,
                                note: $note,
                                workOrderId: (int) ($intervention->work_order_id ?? 0),
                                interventionId: (int) ($intervention->id ?? 0),
                                createdByUserId: Auth::id() !== null ? (int) Auth::id() : null,
                                unitPrice: $unitPrice,
                                fromLocationId: $locationId > 0 ? $locationId : null,
                            );
                        } catch (InvalidArgumentException) {
                            throw ValidationException::withMessages([
                                'from_location_id' => __('inventory.validation.location_mismatch'),
                            ]);
                        }
                    })
                    ->after(function (): void {
                        /** @var Intervention $intervention */
                        $intervention = $this->getOwnerRecord();

                        $intervention->refreshEconomicTotals();
                    }),
            ])
            ->actions([
                DeleteAction::make()
                    ->after(function (): void {
                        /** @var Intervention $intervention */
                        $intervention = $this->getOwnerRecord();

                        $intervention->refreshEconomicTotals();
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    /**
     * @return array<int, string>
     */
    private static function locationOptionsForWarehouse(int $warehouseId): array
    {
        if ($warehouseId <= 0) {
            return [];
        }

        return WarehouseLocation::query()
            ->where('warehouse_id', $warehouseId)
            ->where('is_active', true)
            ->orderBy('code')
            ->pluck('code', 'id')
            ->mapWithKeys(static function (mixed $code, mixed $id): array {
                $codeStr = is_string($code) ? $code : strval($code);
                $idInt = is_numeric($id) ? (int) $id : 0;

                return [$idInt => $codeStr];
            })
            ->all();
    }

    private static function assertLocationBelongsToWarehouse(int $warehouseId, int $locationId, string $field): void
    {
        if ($locationId <= 0) {
            return;
        }

        $location = WarehouseLocation::query()->find($locationId);
        $warehouseIdValue = $location instanceof WarehouseLocation ? $location->getAttribute('warehouse_id') : null;
        $locationWarehouseId = is_numeric($warehouseIdValue) ? (int) $warehouseIdValue : 0;
        if (! $location instanceof WarehouseLocation || $locationWarehouseId !== $warehouseId) {
            throw ValidationException::withMessages([
                $field => __('inventory.validation.location_mismatch'),
            ]);
        }
    }
}
