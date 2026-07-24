<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Filament\Resources\TimberProductResource\Pages;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Models\TimberSupplier;

final class TimberProductResource extends Resource
{
    protected static ?string $model = TimberProduct::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Prodotti';

    protected static ?int $navigationSort = 86;

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
        return 'Anagrafiche';
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'registries',
            operatorFeature: 'registries',
        );
    }

    public static function getModelLabel(): string
    {
        return 'Prodotto';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Prodotti';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('product')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Prodotto')->schema(self::productFields()),
                    Tab::make('Caratteristiche')->schema(self::featureFields()),
                    Tab::make('Magazzino')->schema(self::warehouseFields()),
                ]),
        ]);
    }

    /** @return array<int, Component> */
    private static function productFields(): array
    {
        return [
            Grid::make(2)->components([
                TextInput::make('code')
                    ->label('Cod. prodotto')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('product_type_code')
                    ->label('Tipologia')
                    ->options(self::configMap('timber.product_types'))
                    ->default('lot_managed')
                    ->required(),
                TextInput::make('description')
                    ->label('Descrizione')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('category')
                    ->label('Categoria')
                    ->maxLength(255),
                TextInput::make('subcategory')
                    ->label('Sottocategoria')
                    ->maxLength(255),
                Select::make('unit_of_measure')
                    ->label('U.m.')
                    ->options(self::configMap('timber.units_of_measure'))
                    ->default('quintali')
                    ->required(),
            ]),

            Section::make('Prezzi netti')
                ->components([
                    Repeater::make('list_prices')
                        ->hiddenLabel()
                        ->schema([
                            Select::make('price_list_code')
                                ->label('Listino')
                                ->options(self::configMap('timber.price_lists'))
                                ->required(),
                            TextInput::make('net_price')
                                ->label('Prezzo netto')
                                ->numeric()
                                ->minValue(0)
                                ->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Aggiungi listino')
                        ->defaultItems(0),
                ]),

            Textarea::make('notes')
                ->label('Note')
                ->rows(3)
                ->columnSpanFull(),
        ];
    }

    /** @return array<int, Component> */
    private static function featureFields(): array
    {
        return [
            Grid::make(2)->components([
                TextInput::make('barcode')
                    ->label('Cod. a barre')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('vat_rate_code')
                    ->label('Aliquota IVA')
                    ->options(self::configMap('timber.vat_rates')),
                TextInput::make('commission_class')
                    ->label('Classe provvig.')
                    ->maxLength(255),
                TextInput::make('product_url')
                    ->label('Link')
                    ->url()
                    ->maxLength(255),
                TextInput::make('manufacturer')
                    ->label('Produttore')
                    ->maxLength(255),
            ]),

            Grid::make(2)->components([
                Toggle::make('is_assembled')
                    ->label('Articolo assemblato'),
                Toggle::make('publish_on_ecommerce')
                    ->label('Pubblica su e-commerce'),
            ]),

            Section::make('Campi liberi')
                ->columns(2)
                ->components([
                    TextInput::make('free_1')->label('Libero 1')->maxLength(255),
                    TextInput::make('free_2')->label('Libero 2')->maxLength(255),
                    TextInput::make('free_3')->label('Libero 3')->maxLength(255),
                    TextInput::make('free_4')->label('Libero 4')->maxLength(255),
                ]),

            Section::make('Fornitore')
                ->columns(2)
                ->components([
                    Select::make('supplier_id')
                        ->label('Fornitore')
                        ->options(fn (): array => TimberSupplier::query()
                            ->where('is_active', true)
                            ->orderBy('legal_name')
                            ->pluck('legal_name', 'id')
                            ->all())
                        ->searchable()
                        ->preload(),
                    TextInput::make('supplier_product_code')
                        ->label('Cod. prod. forn.')
                        ->maxLength(255),
                    TextInput::make('supplier_net_price')
                        ->label('Prz forn. netto')
                        ->numeric()
                        ->minValue(0),
                    TextInput::make('supply_notes')
                        ->label('Note fornitura')
                        ->maxLength(255),
                ]),
        ];
    }

    /** @return array<int, Component> */
    private static function warehouseFields(): array
    {
        return [
            Grid::make(2)->components([
                TextInput::make('reorder_multiple')
                    ->label('Ordina multipli di')
                    ->numeric()
                    ->minValue(0),
                TextInput::make('reorder_lead_days')
                    ->label('Riordino (giorni)')
                    ->integer()
                    ->minValue(0),
                TextInput::make('minimum_stock')
                    ->label('Scorta minima')
                    ->numeric()
                    ->minValue(0),
                TextInput::make('warehouse_location')
                    ->label('Ubicazione')
                    ->maxLength(255),
                TextInput::make('average_cost')
                    ->label('Costo medio')
                    ->numeric()
                    ->minValue(0),
                TextInput::make('last_cost')
                    ->label('Ultimo costo')
                    ->numeric()
                    ->minValue(0),
            ]),

            Toggle::make('is_active')
                ->label('Attivo')
                ->default(true),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoria')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Codice')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrizione')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('unit_of_measure')
                    ->label('U.m.')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('product_type_code')
                    ->label('Tipologia')
                    ->formatStateUsing(fn (?string $state): string => self::configMap('timber.product_types')[$state] ?? (string) $state)
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Attivo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product_type_code')
                    ->label('Tipologia')
                    ->options(self::configMap('timber.product_types')),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }

    /**
     * @return array<string, string>
     */
    private static function configMap(string $key): array
    {
        $value = config($key, []);

        if (! is_array($value)) {
            return [];
        }

        /** @var array<string, string> $map */
        $map = [];
        foreach ($value as $code => $label) {
            $map[(string) $code] = is_string($label) ? $label : (string) $code;
        }

        return $map;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberProducts::route('/'),
            'create' => Pages\CreateTimberProduct::route('/create'),
            'view' => Pages\ViewTimberProduct::route('/{record}'),
            'edit' => Pages\EditTimberProduct::route('/{record}/edit'),
        ];
    }
}
