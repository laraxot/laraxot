<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources;

use App\Support\AccountFeatures;
use Closure;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Filament\Resources\TimberStockMovementResource\Pages;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Models\TimberStockMovement;

final class TimberStockMovementResource extends Resource
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->components([
                    DatePicker::make('movement_date')
                        ->label('Data')
                        ->default(now())
                        ->required(),
                    Select::make('direction_code')
                        ->label('Tipo movimento')
                        ->options(self::configMap('timber.stock_movement_directions'))
                        ->default('load')
                        ->required(),
                    Select::make('product_id')
                        ->label('Prodotto')
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
                            ->label('Quantita')
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
                            ->label('U.m.')
                            ->key('unit_of_measure')
                            ->options(self::configMap('timber.units_of_measure'))
                            ->default('quintali')
                            ->required(),
                    ]),
                    Select::make('customer_id')
                        ->label('Cliente / Fornitore')
                        ->relationship('customer', 'business_name')
                        ->searchable()
                        ->preload(),
                    Select::make('document_type_code')
                        ->label('Tipo documento')
                        ->options(self::configMap('timber.document_types')),
                    TextInput::make('document_reference')
                        ->label('Causale / Documento')
                        ->maxLength(255),
                    TextInput::make('lot_code')
                        ->label('Lotto / Seriale')
                        ->maxLength(255),
                    TextInput::make('unit_cost')
                        ->label('Costo unitario')
                        ->numeric()
                        ->minValue(0)
                        ->helperText('Aggiorna costo medio e ultimo costo del prodotto sui carichi'),
                    Textarea::make('notes')
                        ->label('Note')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('movement_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('movement_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.code')
                    ->label('Cod.')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('product.description')
                    ->label('Prodotto')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('customer.business_name')
                    ->label('Cliente / Fornitore')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('document_reference')
                    ->label('Causale')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('loaded')
                    ->label('Caricato')
                    ->getStateUsing(fn (TimberStockMovement $record): ?string => $record->signedQuantity() > 0
                        ? rtrim(rtrim(number_format(abs($record->signedQuantity()), 3, ',', '.'), '0'), ',')
                        : null)
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('lot_code')
                    ->label('Lotto/Seriale')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('unloaded')
                    ->label('Scaricato')
                    ->getStateUsing(fn (TimberStockMovement $record): ?string => $record->signedQuantity() < 0
                        ? rtrim(rtrim(number_format(abs($record->signedQuantity()), 3, ',', '.'), '0'), ',')
                        : null)
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoria')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('direction_code')
                    ->label('Stato')
                    ->options(self::configMap('timber.stock_movement_directions')),
                Tables\Filters\SelectFilter::make('product_id')
                    ->label('Prodotto')
                    ->relationship('product', 'description')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('customer_id')
                    ->label('Cliente / Fornitore')
                    ->relationship('customer', 'business_name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('lot_code')
                    ->schema([
                        TextInput::make('lot_code')->label('Lotto / Seriale'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = trim((string) ($data['lot_code'] ?? ''));

                        return $value === ''
                            ? $query
                            : $query->where('lot_code', 'like', "%{$value}%");
                    }),
                Tables\Filters\Filter::make('period')
                    ->schema([
                        DatePicker::make('from')->label('Dal'),
                        DatePicker::make('until')->label('Al'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, mixed $date): Builder => $q->whereDate('movement_date', '>=', is_string($date) ? $date : null))
                            ->when($data['until'] ?? null, fn (Builder $q, mixed $date): Builder => $q->whereDate('movement_date', '<=', is_string($date) ? $date : null));
                    }),
            ])
            ->actions([
                ViewAction::make(),
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
            'index' => Pages\ListTimberStockMovements::route('/'),
            'create' => Pages\CreateTimberStockMovement::route('/create'),
            'view' => Pages\ViewTimberStockMovement::route('/{record}'),
        ];
    }
}
