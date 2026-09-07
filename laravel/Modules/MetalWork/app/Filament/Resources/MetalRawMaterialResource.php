<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;

/**
 * The raw-material list, built by importing the purchase invoices rather than
 * typed by hand. The current price column is the last invoiced one — the same
 * number the estimate uses — so a wrong quotation can be traced to the invoice
 * behind it in one click.
 */
final class MetalRawMaterialResource extends GatedXotBaseResource
{
    protected static ?string $model = MetalRawMaterial::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?int $navigationSort = 30;

    public static function getNavigationGroup(): string
    {
        return (string) __('metalwork::metalwork.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('metalwork::metalwork.raw_material');
    }

    public static function getPluralModelLabel(): string
    {
        return __('metalwork::metalwork.raw_materials');
    }

    /**
     * @return array<string, string>
     */
    public static function familyOptions(): array
    {
        $families = ['sheet', 'tube', 'beam', 'flat', 'angle', 'round', 'square', 'oxy_cut', 'mesh', 'other'];

        return array_combine(
            $families,
            array_map(static fn (string $family): string => (string) __('metalwork::metalwork.families.'.$family), $families),
        );
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
                        TextInput::make('description')
                            ->required(),
                    ]),
                    Grid::make(4)->components([
                        Select::make('family_code')
                            ->options(self::familyOptions())
                            ->default('other')
                            ->required(),
                        Select::make('unit_code')
                            ->options(['kg' => 'kg', 'ml' => 'ml', 'mq' => 'mq', 'nr' => 'nr'])
                            ->default('kg')
                            ->required(),
                        TextInput::make('steel_grade'),
                        TextInput::make('supplier_name'),
                    ]),
                    Grid::make(4)->components([
                        TextInput::make('thickness_mm')->numeric(),
                        TextInput::make('width_mm')->numeric(),
                        TextInput::make('height_mm')->numeric(),
                        TextInput::make('length_mm')->numeric(),
                    ]),
                    Grid::make(3)->components([
                        TextInput::make('density_kg_dm3')
                            ->numeric()
                            ->placeholder('7,85'),
                        TextInput::make('weight_per_meter_kg')
                            ->numeric(),
                        Toggle::make('is_active')->default(true),
                    ]),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('description')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('family_code')
                    ->formatStateUsing(fn (?string $state): string => (string) __('metalwork::metalwork.families.'.($state ?? 'other'))),
                Tables\Columns\TextColumn::make('steel_grade')->placeholder('—'),
                Tables\Columns\TextColumn::make('unit_code'),
                Tables\Columns\TextColumn::make('current_price')
                    ->placeholder('—')
                    ->state(fn (MetalRawMaterial $record): ?string => $record->currentUnitPrice() === null
                        ? null
                        : number_format($record->currentUnitPrice(), 4, ',', '.').' €/'.$record->unit_code),
                Tables\Columns\TextColumn::make('supplier_name')->placeholder('—')->searchable(),
                Tables\Columns\TextColumn::make('supplier_prices_count')
                    ->counts('supplierPrices'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('family_code')
                    ->options(self::familyOptions()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MetalRawMaterialResource\RelationManagers\SupplierPricesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => MetalRawMaterialResource\Pages\ListMetalRawMaterials::route('/'),
            'edit' => MetalRawMaterialResource\Pages\EditMetalRawMaterial::route('/{record}/edit'),
        ];
    }
}
