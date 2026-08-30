<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignCatalogItemResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Costing\Models\WorkCenter;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignCatalogItemComponent;
use Modules\Signage\Models\SignPriceListItem;
use Modules\Signage\Support\SignageCosting;

/**
 * The distinta of a catalog item. Flat, one level — the customer has no
 * sub-assemblies. Picking a supplier item fills the description, unit and cost,
 * which the operator can still overwrite for a one-off.
 */
final class ComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'components';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return (string) __('signage::signage.components');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)->components([
                TextInput::make('position')
                    ->label(__('signage::signage.catalog_fields.position'))
                    ->numeric()
                    ->default(function (): int {
                        // La riga nuova si mette in coda, lasciando spazio per
                        // infilarne altre a mano fra due esistenti.
                        $owner = $this->getOwnerRecord();
                        $last = $owner instanceof SignCatalogItem
                            ? $owner->components()->max('position')
                            : null;

                        return (is_numeric($last) ? (int) $last : 0) + 10;
                    })
                    ->required(),
                Select::make('work_center_code')
                    ->label(__('signage::signage.catalog_fields.work_center_code'))
                    ->options(fn (): array => WorkCenter::query()
                        ->forContext(SignageCosting::CONTEXT)
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->pluck('name', 'code')
                        ->all())
                    ->required(),
                Select::make('sign_price_list_item_id')
                    ->label(__('signage::signage.supplier_item'))
                    ->options(fn (): array => SignPriceListItem::query()
                        ->where('is_active', true)
                        ->orderBy('code')
                        ->get()
                        ->mapWithKeys(fn (SignPriceListItem $i): array => [$i->id => $i->code.' — '.$i->name])
                        ->all())
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set): void {
                        $item = $state === null ? null : SignPriceListItem::query()->find($state);
                        if (! $item instanceof SignPriceListItem) {
                            return;
                        }

                        $set('description', $item->name);
                        $set('unit_code', $item->unit_code);
                        $set('unit_cost', $item->unit_cost);
                        if ($item->work_center_code !== null) {
                            $set('work_center_code', $item->work_center_code);
                        }
                    }),
            ]),
            TextInput::make('description')
                ->label(__('signage::signage.catalog_fields.description'))
                ->required()
                ->columnSpanFull(),
            Grid::make(5)->components([
                Select::make('unit_code')
                    ->label(__('signage::signage.fields.unit_code'))
                    ->options(__('signage::signage.units'))
                    ->required()
                    ->live(),
                TextInput::make('quantity')
                    ->label(__('signage::signage.catalog_fields.quantity'))
                    ->numeric()
                    ->default(1)
                    ->required(),
                TextInput::make('width_mm')
                    ->label(__('signage::signage.catalog_fields.width_mm'))
                    ->numeric()
                    ->helperText(fn (Get $get): ?string => self::isSurfaceUnit($get('unit_code'))
                        ? (string) __('signage::signage.pricing.surface_hint')
                        : null),
                TextInput::make('height_mm')
                    ->label(__('signage::signage.catalog_fields.height_mm'))
                    ->numeric(),
                TextInput::make('unit_cost')
                    ->label(__('signage::signage.fields.unit_cost'))
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->columns([
                Tables\Columns\TextColumn::make('position')->label(__('signage::signage.catalog_fields.position'))->sortable(),
                Tables\Columns\TextColumn::make('work_center_code')
                    ->label(__('signage::signage.catalog_fields.work_center_code'))
                    ->badge(),
                Tables\Columns\TextColumn::make('supplierItem.code')
                    ->label(__('signage::signage.fields.code'))
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('supplierItem.supplier_name')
                    ->label(__('signage::signage.fields.supplier_name'))
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')->label(__('signage::signage.catalog_fields.description'))->wrap()->limit(60),
                Tables\Columns\TextColumn::make('unit_code')->label(__('signage::signage.fields.unit_code')),
                Tables\Columns\TextColumn::make('quantity')->label(__('signage::signage.catalog_fields.quantity'))->numeric(3),
                Tables\Columns\TextColumn::make('width_mm')->label(__('signage::signage.catalog_fields.width_mm'))->placeholder('—'),
                Tables\Columns\TextColumn::make('height_mm')->label(__('signage::signage.catalog_fields.height_mm'))->placeholder('—'),
                Tables\Columns\TextColumn::make('unit_cost')->label(__('signage::signage.fields.unit_cost'))->money('EUR'),
                Tables\Columns\TextColumn::make('line_cost')
                    ->label(__('signage::signage.catalog_fields.line_cost'))
                    ->state(fn (SignCatalogItemComponent $record): float => $record->lineCost())
                    ->money('EUR')
                    ->weight('bold'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    private static function isSurfaceUnit(mixed $unitCode): bool
    {
        $surfaceUnits = config('signage.surface_unit_codes', ['mq']);
        if (! is_array($surfaceUnits)) {
            return false;
        }

        $codes = array_map(static fn (mixed $unit): string => is_scalar($unit) ? (string) $unit : '', $surfaceUnits);

        return in_array(is_scalar($unitCode) ? (string) $unitCode : '', $codes, true);
    }
}
