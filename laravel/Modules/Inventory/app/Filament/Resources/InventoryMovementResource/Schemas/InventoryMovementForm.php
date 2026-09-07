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
use Illuminate\Support\Carbon;
use Modules\Inventory\Filament\Resources\InventoryMovementResource;
use Modules\Inventory\Models\Warehouse;
use Modules\Xot\Actions\Cast\SafeIntCastAction;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class InventoryMovementForm extends XotBaseResourceForm
{
    protected static string $resource = InventoryMovementResource::class;

    /**
     * @return array<int, Component>
     */
    public function getFormSchema(): array
    {
        $storageZonesEnabled = InventoryMovementResource::storageZonesEnabled();
        $hasMovementType = fn (callable $get): bool => trim(SafeStringCastAction::cast($get('movement_type'))) !== '';

        return [
            Section::make()
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
                            ->options(fn (callable $get): array => InventoryMovementResource::productOptionsForMovement(
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
                            ->options(fn (callable $get): array => InventoryMovementResource::locationOptionsForWarehouse(SafeIntCastAction::cast($get('from_warehouse_id'))))
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
                            ->options(fn (callable $get): array => InventoryMovementResource::locationOptionsForWarehouse(SafeIntCastAction::cast($get('to_warehouse_id'))))
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
                        ->state(fn (callable $get): string => InventoryMovementResource::sourceStockHint(
                            SafeStringCastAction::cast($get('movement_type')),
                            SafeIntCastAction::cast($get('product_id')),
                            SafeIntCastAction::cast($get('from_warehouse_id')),
                            SafeIntCastAction::cast($get('from_location_id')),
                        ))
                        ->visible(fn (callable $get): bool => in_array(SafeStringCastAction::cast($get('movement_type')), ['scarico', 'trasferimento', 'reso'], true)),
                    Grid::make(2)->components([
                        Select::make('work_order_id')
                            ->label(__('inventory.fields.work_order'))
                            ->options(fn (): array => InventoryMovementResource::workOrderOptions())
                            ->searchable()
                            ->preload()
                            ->visible($hasMovementType),
                        Select::make('intervention_id')
                            ->label(__('inventory.fields.intervention'))
                            ->options(fn (): array => InventoryMovementResource::interventionOptions())
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
}
