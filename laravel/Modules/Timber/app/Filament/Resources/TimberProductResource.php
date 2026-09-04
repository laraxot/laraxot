<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Resources\TimberProductResource\Pages;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Models\TimberSupplier;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class TimberProductResource extends XotBaseResource
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

    /** @return array<int|string, Component> */
    public static function getFormSchema(): array
    {
        return [
            Tabs::make('product')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Prodotto')->schema(self::productFields()),
                    Tab::make('Caratteristiche')->schema(self::featureFields()),
                    Tab::make('Magazzino')->schema(self::warehouseFields()),
                ]),
        ];
    }

    /** @return array<int, Component> */
    private static function productFields(): array
    {
        return [
            Grid::make(2)->components([
                TextInput::make('code')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('product_type_code')
                    ->options(TimberConfigMap::get('timber.product_types'))
                    ->default('lot_managed')
                    ->required(),
                TextInput::make('description')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('category')
                    ->maxLength(255),
                TextInput::make('subcategory')
                    ->maxLength(255),
                Select::make('unit_of_measure')
                    ->options(TimberConfigMap::get('timber.units_of_measure'))
                    ->default('quintali')
                    ->required(),
            ]),

            Section::make('Prezzi netti')
                ->components([
                    Repeater::make('list_prices')
                        ->hiddenLabel()
                        ->schema([
                            Select::make('price_list_code')
                                ->options(TimberConfigMap::get('timber.price_lists'))
                                ->required(),
                            TextInput::make('net_price')
                                ->numeric()
                                ->minValue(0)
                                ->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Aggiungi listino')
                        ->defaultItems(0),
                ]),

            Textarea::make('notes')
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
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('vat_rate_code')
                    ->options(TimberConfigMap::get('timber.vat_rates')),
                TextInput::make('commission_class')
                    ->maxLength(255),
                TextInput::make('product_url')
                    ->url()
                    ->maxLength(255),
                TextInput::make('manufacturer')
                    ->maxLength(255),
            ]),

            Grid::make(2)->components([
                Toggle::make('is_assembled'),
                Toggle::make('publish_on_ecommerce'),
            ]),

            Section::make('Campi liberi')
                ->columns(2)
                ->components([
                    TextInput::make('free_1')->maxLength(255),
                    TextInput::make('free_2')->maxLength(255),
                    TextInput::make('free_3')->maxLength(255),
                    TextInput::make('free_4')->maxLength(255),
                ]),

            Section::make('Fornitore')
                ->columns(2)
                ->components([
                    Select::make('supplier_id')
                        ->options(fn (): array => TimberSupplier::query()
                            ->where('is_active', true)
                            ->orderBy('legal_name')
                            ->pluck('legal_name', 'id')
                            ->all())
                        ->searchable()
                        ->preload(),
                    TextInput::make('supplier_product_code')
                        ->maxLength(255),
                    TextInput::make('supplier_net_price')
                        ->numeric()
                        ->minValue(0),
                    TextInput::make('supply_notes')
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
                    ->numeric()
                    ->minValue(0),
                TextInput::make('reorder_lead_days')
                    ->integer()
                    ->minValue(0),
                TextInput::make('minimum_stock')
                    ->numeric()
                    ->minValue(0),
                TextInput::make('warehouse_location')
                    ->maxLength(255),
                TextInput::make('average_cost')
                    ->numeric()
                    ->minValue(0),
                TextInput::make('last_cost')
                    ->numeric()
                    ->minValue(0),
            ]),

            Toggle::make('is_active')
                ->default(true),
        ];
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
