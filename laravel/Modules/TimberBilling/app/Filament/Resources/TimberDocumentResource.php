<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Actions\PostTimberDocumentStockAction;
use Modules\TimberBilling\Filament\Resources\TimberDocumentResource\Pages;
use Modules\TimberBilling\Models\TimberDocument;
use Modules\TimberBilling\Models\TimberProduct;
use Modules\TimberBilling\Queries\GetTimberProductStockByLotQuery;

final class TimberDocumentResource extends Resource
{
    protected static ?string $model = TimberDocument::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Documenti';

    protected static ?int $navigationSort = 30;

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
        return 'Documento';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Documenti';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Testata')
                ->columns(3)
                ->components([
                    Select::make('document_type_code')
                        ->label('Tipo documento')
                        ->options(self::configMap('timber.document_types'))
                        ->default('ddt')
                        ->required(),
                    TextInput::make('number')
                        ->label('Numero')
                        ->placeholder('Assegnato automaticamente')
                        ->maxLength(255),
                    DatePicker::make('document_date')
                        ->label('Data')
                        ->default(now())
                        ->required(),
                    Select::make('customer_id')
                        ->label('Cliente / Fornitore')
                        ->relationship('customer', 'business_name')
                        ->searchable()
                        ->preload(),
                    Select::make('status_code')
                        ->label('Stato')
                        ->options(self::configMap('timber.document_statuses'))
                        ->default('draft')
                        ->required(),
                    TextInput::make('reference')
                        ->label('Riferimento / Causale')
                        ->maxLength(255),
                ]),

            Section::make('Righe')
                ->components([
                    Repeater::make('lines')
                        ->relationship()
                        ->hiddenLabel()
                        ->schema([
                            Select::make('product_id')
                                ->label('Prodotto')
                                ->options(fn (): array => TimberProduct::query()
                                    ->where('is_active', true)
                                    ->orderBy('description')
                                    ->pluck('description', 'id')
                                    ->all())
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function (Set $set, mixed $state): void {
                                    $product = is_numeric($state) ? TimberProduct::query()->find($state) : null;
                                    if ($product === null) {
                                        return;
                                    }

                                    $set('description', $product->description);
                                    $set('unit_of_measure', $product->unit_of_measure);
                                    $set('unit_price', (float) ($product->priceForList('ben_b2b') ?? $product->last_cost ?? 0));

                                    // DDT intelligente: propone il lotto a magazzino da scaricare.
                                    $lots = app(GetTimberProductStockByLotQuery::class)->execute((int) $product->id);
                                    if ($lots !== []) {
                                        $best = $lots[0];
                                        $set('lot_code', $best['lot_code']);
                                        Notification::make()
                                            ->title('Lotto a magazzino: '.$best['lot_code'])
                                            ->body('Disponibili '.self::formatQuantity($best['available']).' — verra scaricato dal magazzino all\'emissione del DDT.')
                                            ->info()
                                            ->send();
                                    }
                                })
                                ->columnSpan(2),
                            TextInput::make('description')
                                ->label('Descrizione')
                                ->required()
                                ->columnSpan(2),
                            TextInput::make('quantity')
                                ->label('Quantita')
                                ->numeric()
                                ->minValue(0)
                                ->default(1)
                                ->required(),
                            Select::make('unit_of_measure')
                                ->label('U.m.')
                                ->options(self::configMap('timber.units_of_measure'))
                                ->default('quintali')
                                ->required(),
                            TextInput::make('unit_price')
                                ->label('Prezzo unit.')
                                ->numeric()
                                ->minValue(0)
                                ->default(0),
                            TextInput::make('discount_percent')
                                ->label('Sconto %')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->default(0),
                            TextInput::make('lot_code')
                                ->label('Lotto a magazzino')
                                ->maxLength(255)
                                ->helperText(function (Get $get): ?string {
                                    $productId = $get('product_id');
                                    if (! is_numeric($productId)) {
                                        return null;
                                    }

                                    $total = app(GetTimberProductStockByLotQuery::class)->totalAvailable((int) $productId);

                                    return $total > 0
                                        ? 'A magazzino: '.self::formatQuantity($total).' disponibili'
                                        : 'Nessuna giacenza a magazzino per questo prodotto';
                                }),
                        ])
                        ->columns(4)
                        ->addActionLabel('Aggiungi riga')
                        ->defaultItems(0),
                ]),

            Textarea::make('notes')
                ->label('Note')
                ->rows(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('document_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('document_type_code')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => self::configMap('timber.document_types')[$state] ?? (string) $state)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('number')
                    ->label('Numero')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.business_name')
                    ->label('Cliente / Fornitore')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status_code')
                    ->label('Stato')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => self::configMap('timber.document_statuses')[$state] ?? (string) $state),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Totale')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('stock_posted_at')
                    ->label('A magazzino')
                    ->boolean()
                    ->getStateUsing(fn (TimberDocument $record): bool => $record->stock_posted_at !== null),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('document_type_code')
                    ->label('Tipo')
                    ->options(self::configMap('timber.document_types')),
                Tables\Filters\SelectFilter::make('status_code')
                    ->label('Stato')
                    ->options(self::configMap('timber.document_statuses')),
            ])
            ->actions([
                self::postToStockAction(),
                ViewAction::make(),
                EditAction::make(),
            ]);
    }

    public static function postToStockAction(): Action
    {
        return Action::make('postToStock')
            ->label('Registra a magazzino')
            ->icon('heroicon-o-arrow-down-on-square')
            ->color('success')
            ->requiresConfirmation()
            ->visible(fn (TimberDocument $record): bool => $record->hasStockImpact() && $record->stock_posted_at === null)
            ->action(function (TimberDocument $record): void {
                try {
                    app(PostTimberDocumentStockAction::class)->execute($record, (int) Auth::id());
                    Notification::make()->title('Documento registrato a magazzino')->success()->send();
                } catch (\RuntimeException $exception) {
                    Notification::make()->title($exception->getMessage())->warning()->send();
                }
            });
    }

    private static function formatQuantity(float $quantity): string
    {
        return rtrim(rtrim(number_format($quantity, 3, ',', '.'), '0'), ',');
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
            'index' => Pages\ListTimberDocuments::route('/'),
            'create' => Pages\CreateTimberDocument::route('/create'),
            'view' => Pages\ViewTimberDocument::route('/{record}'),
            'edit' => Pages\EditTimberDocument::route('/{record}/edit'),
        ];
    }
}
