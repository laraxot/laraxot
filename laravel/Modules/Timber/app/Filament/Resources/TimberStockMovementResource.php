<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Resources\TimberStockMovementResource\Pages;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Models\TimberStockMovement;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class TimberStockMovementResource extends XotBaseResource
{
    protected static ?string $model = TimberStockMovement::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-up-down';

    protected static ?string $navigationLabel = 'Movimenti magazzino';

    protected static ?int $navigationSort = 40;

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

    public static function getNavigationGroup(): string
    {
        return 'Magazzino';
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'access',
            operatorFeature: 'access',
        );
    }

    public static function getModelLabel(): string
    {
        return 'Movimento';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Movimenti magazzino';
    }

    /** @return array<int|string, Component> */
    public function getFormSchema(): array
    {
        return [
            Section::make()
                ->columns(2)
                ->components([
                    DatePicker::make('movement_date')
                        ->default(now())
                        ->required(),
                    Select::make('direction_code')
                        ->options(TimberConfigMap::get('timber.stock_movement_directions'))
                        ->default('load')
                        ->required(),
                    Select::make('product_id')
                        ->options(fn (): array => TimberProduct::query()
                            ->where('is_active', true)
                            ->orderBy('description')
                            ->pluck('description', 'id')
                            ->all())
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Set $set, mixed $state): void {
                            $product = is_numeric($state) ? TimberProduct::query()->find($state) : null;
                            if ($product !== null) {
                                $set('unit_of_measure', $product->unit_of_measure);
                            }
                        }),
                    Grid::make(2)->components([
                        TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->rule(static function (Get $get): Closure {
                                return static function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                                    $quantity = is_numeric($value) ? (float) $value : 0.0;
                                    $isAdjustment = $get('direction_code') === 'adjustment';

                                    if ($isAdjustment && abs($quantity) < 0.0001) {
                                        $fail('La rettifica non puo essere nulla.');

                                        return;
                                    }

                                    if (! $isAdjustment && $quantity <= 0) {
                                        $fail('La quantita deve essere positiva.');
                                    }
                                };
                            }),
                        Select::make('unit_of_measure')
                            ->key('unit_of_measure')
                            ->options(TimberConfigMap::get('timber.units_of_measure'))
                            ->default('quintali')
                            ->required(),
                    ]),
                    Select::make('customer_id')
                        ->relationship('customer', 'business_name')
                        ->searchable()
                        ->preload(),
                    Select::make('document_type_code')
                        ->options(TimberConfigMap::get('timber.document_types')),
                    TextInput::make('document_reference')
                        ->maxLength(255),
                    TextInput::make('lot_code')
                        ->maxLength(255),
                    TextInput::make('unit_cost')
                        ->numeric()
                        ->minValue(0)
                        ->helperText('Aggiorna costo medio e ultimo costo del prodotto sui carichi'),
                    Textarea::make('notes')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberStockMovements::route('/'),
            'create' => Pages\CreateTimberStockMovement::route('/create'),
            'view' => Pages\ViewTimberStockMovement::route('/{record}'),
        ];
    }
}
