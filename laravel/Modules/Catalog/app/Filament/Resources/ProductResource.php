<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Catalog\Filament\Resources\ProductResource\Pages;
use Modules\Catalog\Models\Product;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class ProductResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Product::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    /** @var string */
    protected const ACCOUNT_FEATURE_ADMIN = 'admin.catalog';

    /** @var string */
    protected const ACCOUNT_FEATURE_OPERATOR = 'operator.catalog';

    public static function getNavigationGroup(): string
    {
        return __('catalog.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('catalog.product');
    }

    public static function getPluralModelLabel(): string
    {
        return __('catalog.products');
    }

    /**
     * @return array<int, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    TextInput::make('name')
                        ->label(__('catalog.fields.name'))
                        ->required()
                        ->maxLength(255),
                    TextInput::make('sku')
                        ->label(__('catalog.fields.sku'))
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label(__('catalog.fields.description'))
                        ->columnSpanFull(),
                    Grid::make(2)->components([
                        TextInput::make('unit_price')
                            ->label(__('catalog.fields.unit_price'))
                            ->numeric()
                            ->minValue(0),
                        Toggle::make('is_active')
                            ->label(__('catalog.fields.is_active'))
                            ->default(true),
                    ]),
                    Grid::make(2)->components([
                        Select::make('catalog_model_id')
                            ->label(__('catalog.model'))
                            ->relationship('model', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('catalog_part_type_id')
                            ->label(__('catalog.part_type'))
                            ->relationship('partType', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                    Grid::make(2)->components([
                        Select::make('catalog_measure_unit_id')
                            ->label(__('catalog.measure_unit'))
                            ->relationship('measureUnit', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('catalog_frequency_id')
                            ->label(__('catalog.frequency'))
                            ->relationship('frequency', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
                ])
                ->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('catalog.fields.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label(__('catalog.fields.sku'))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label(__('catalog.fields.unit_price'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('catalog.fields.is_active'))
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
