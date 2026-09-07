<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Costing\Models\WorkCenter;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\Signage\Filament\Resources\SignPriceListItemResource\Pages;
use Modules\Signage\Models\SignPriceListItem;
use Modules\Signage\Support\SignageCosting;

/**
 * The supplier price list: what every material, machining step and outsourced
 * job costs, grouped by the work centre it belongs to. It is the cost side —
 * the sales side lives in SignPriceListResource.
 */
final class SignPriceListItemResource extends GatedXotBaseResource
{
    protected static ?string $model = SignPriceListItem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): string
    {
        return (string) __('signage::signage.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('signage::signage.supplier_item');
    }

    public static function getPluralModelLabel(): string
    {
        return __('signage::signage.supplier_items');
    }

    /**
     * @return array<string, Component|Field>
     */
    public static function getFormSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('category_code')
                            ->options(__('signage::signage.categories'))
                            ->required(),
                    ]),
                    Select::make('work_center_code')
                        ->options(fn (): array => WorkCenter::query()
                            ->forContext(SignageCosting::CONTEXT)
                            ->where('is_active', true)
                            ->orderBy('sort_order')
                            ->pluck('name', 'code')
                            ->all())
                        ->searchable()
                        ->preload(),
                    TextInput::make('name')
                        ->required()
                        ->columnSpanFull(),
                    Grid::make(3)->components([
                        Select::make('unit_code')
                            ->options(__('signage::signage.units'))
                            ->required(),
                        TextInput::make('unit_cost')
                            ->numeric()
                            ->required()
                            ->default(0),
                        TextInput::make('currency')
                            ->default('EUR')
                            ->maxLength(3),
                    ]),
                    Grid::make(2)->components([
                        TextInput::make('supplier_name')->nullable(),
                        TextInput::make('supplier_code')->nullable(),
                    ]),
                    Textarea::make('notes')->rows(2),
                    Toggle::make('is_active')->default(true),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('category_code')
                    ->formatStateUsing(fn (string $state): string => __('signage::signage.categories.'.$state))
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('work_center_code')
                    ->placeholder('—')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier_name')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('unit_code'),
                Tables\Columns\TextColumn::make('unit_cost')->money('EUR', divideBy: 1)->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_code')
                    ->options(function (): array {
                        /** @var array<string, string> $categories */
                        $categories = (array) __('signage::signage.categories');

                        return $categories;
                    }),
                Tables\Filters\SelectFilter::make('work_center_code')
                    ->options(fn (): array => WorkCenter::query()
                        ->forContext(SignageCosting::CONTEXT)
                        ->orderBy('sort_order')
                        ->pluck('name', 'code')
                        ->all()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SignPriceListItemResource\RelationManagers\PriceEntriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignPriceListItems::route('/'),
            'view' => Pages\ViewSignPriceListItem::route('/{record}'),
        ];
    }
}
