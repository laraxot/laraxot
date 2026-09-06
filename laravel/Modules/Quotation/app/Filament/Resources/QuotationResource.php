<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources;

use App\Support\AccountFeatures;
use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\Customer;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\Quotation\Filament\Resources\QuotationResource\Pages;
use Modules\Quotation\Filament\Resources\QuotationResource\RelationManagers\AttachmentsRelationManager;
use Modules\Quotation\Filament\Resources\QuotationResource\RelationManagers\ItemsRelationManager;
use Modules\Quotation\Models\Quotation;

use function Safe\json_encode;

final class QuotationResource extends GatedXotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Quotation::class;

    /**
     * Ricerca globale: l'operatore cerca un codice o un nome senza sapere in che
     * sezione si trova. I dettagli evitano di dover aprire il record per capire
     * se e' quello giusto.
     */
    protected static ?string $recordTitleAttribute = 'code';

    /**
     * @return array<int, string>
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'title', 'customer.legal_name'];
    }

    /**
     * @return array<string, string|null>
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Quotation $record */
        $details = [];

        $customerName = $record->customer?->legal_name;
        if (is_string($customerName) && $customerName !== '') {
            $details[__('quotations.fields.customer')] = $customerName;
        }

        $statusCode = $record->status_code;
        if ($statusCode !== '') {
            $details[__('quotations.fields.status')] = (string) __('quotations.statuses.'.$statusCode);
        }

        return $details;
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    /** Read reflectively by HasAccountFeatureVisibility via defined()/constant(). */
    private const ACCOUNT_FEATURE_ADMIN = 'admin.quotations';

    /** Read reflectively by HasAccountFeatureVisibility via defined()/constant(). */
    private const ACCOUNT_FEATURE_OPERATOR = 'operator.quotations';

    public static function getNavigationGroup(): string
    {
        return __('quotations.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('quotations.quotation');
    }

    public static function getPluralModelLabel(): string
    {
        return __('quotations.quotations');
    }

    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('code')
                            ->label(__('quotations.fields.code'))
                            ->maxLength(255),
                        Select::make('status_code')
                            ->label(__('quotations.fields.status'))
                            ->options([
                                'draft' => __('quotations.statuses.draft'),
                                'sent' => __('quotations.statuses.sent'),
                                'confirmed' => __('quotations.statuses.confirmed'),
                                'rejected' => __('quotations.statuses.rejected'),
                            ])
                            ->required()
                            ->default('draft'),
                    ]),

                    TextInput::make('project')
                        ->label(__('quotations.fields.project'))
                        ->helperText(__('quotations.fields.project_hint'))
                        ->maxLength(255),

                    Select::make('customer_id')
                        ->label(__('quotations.fields.customer'))
                        ->options(fn (): array => DB::table('customers')->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(fn (callable $set) => $set('work_order_id', null))
                        ->required()
                        ->createOptionForm([
                            TextInput::make('legal_name')->label(__('quotations.fields.customer'))->required()->maxLength(255),
                            TextInput::make('vat_number')->maxLength(255),
                            TextInput::make('city')->label(__('quotations.voice.city'))->maxLength(255),
                            TextInput::make('email')->email()->maxLength(255),
                            TextInput::make('phone')->label(__('quotations.voice.phone'))->maxLength(255),
                        ])
                        ->createOptionUsing(function (array $data): int {
                            $key = Customer::query()->create([
                                'legal_name' => $data['legal_name'],
                                'vat_number' => $data['vat_number'] ?? null,
                                'city' => $data['city'] ?? null,
                                'email' => $data['email'] ?? null,
                                'phone' => $data['phone'] ?? null,
                                'is_active' => true,
                            ])->getKey();

                            return is_numeric($key) ? (int) $key : 0;
                        }),

                    Select::make('work_order_id')
                        ->label(__('quotations.fields.work_order'))
                        ->options(function (callable $get): array {
                            $q = DB::table('work_orders')->orderByDesc('id');
                            $customerId = $get('customer_id');
                            if (! blank($customerId)) {
                                $q->where('customer_id', $customerId);
                            }

                            return $q
                                ->limit(200)
                                ->get(['id', 'code', 'title'])
                                ->mapWithKeys(fn ($row): array => [
                                    (int) $row->id => (string) ($row->code ?: $row->title ?: 'Commessa senza codice'),
                                ])
                                ->all();
                        })
                        ->searchable()
                        ->preload(),

                    TextInput::make('title')
                        ->label(__('quotations.fields.title'))
                        ->maxLength(255),

                    TextInput::make('tax_percent')
                        ->label(__('quotations.fields.tax_percent'))
                        ->numeric()
                        ->minValue(0)
                        ->default(22),

                    Grid::make(2)->components([
                        DateTimePicker::make('issued_at')->label(__('quotations.fields.issued_at')),
                        DateTimePicker::make('confirmed_at')->label(__('quotations.fields.confirmed_at')),
                    ]),

                    Grid::make(2)->components([
                        DateTimePicker::make('delivery_at')->label(__('quotations.fields.delivery_at')),
                        TextInput::make('lead_time_days')
                            ->label(__('quotations.fields.lead_time_days'))
                            ->helperText(__('quotations.fields.lead_time_days_hint'))
                            ->numeric()
                            ->minValue(0),
                    ]),

                    Textarea::make('notes')
                        ->label(__('quotations.fields.notes'))
                        ->columnSpanFull(),

                    Grid::make(3)->components([
                        TextInput::make('subtotal')
                            ->label(__('quotations.fields.subtotal'))
                            ->numeric()
                            ->disabled(),
                        TextInput::make('discount_amount')
                            ->label(__('quotations.fields.discount_amount'))
                            ->numeric()
                            ->disabled(),
                        TextInput::make('taxable_amount')
                            ->label(__('quotations.fields.taxable_amount'))
                            ->numeric()
                            ->disabled(),
                    ]),
                    Grid::make(2)->components([
                        TextInput::make('tax_amount')
                            ->label(__('quotations.fields.tax_amount'))
                            ->numeric()
                            ->disabled(),
                        TextInput::make('total')
                            ->label(__('quotations.fields.total'))
                            ->numeric()
                            ->disabled(),
                    ]),

                    Grid::make(2)->components([
                        TextInput::make('cost_total')
                            ->label(__('quotations.fields.cost_total'))
                            ->helperText(__('quotations.fields.cost_total_hint'))
                            ->numeric()
                            ->minValue(0),
                        Placeholder::make('margin_summary')
                            ->label(__('quotations.margin.label'))
                            ->content(fn (?Quotation $record): string => self::marginSummary($record)),
                    ]),
                ])
                ->columns(2),

            Section::make(__('quotations.voice.section_title'))
                ->collapsible()
                ->collapsed()
                ->columnSpanFull()
                ->visible(function ($record): bool {
                    if (! $record instanceof Quotation) {
                        return false;
                    }

                    return filled($record->voice_transcript) || filled($record->voice_ai_response);
                })
                ->components([
                    Textarea::make('voice_transcript')
                        ->label(__('quotations.voice.transcript_label'))
                        ->disabled()
                        ->rows(3)
                        ->columnSpanFull(),
                    Textarea::make('voice_ai_response')
                        ->label(__('quotations.voice.ai_response_label'))
                        ->disabled()
                        ->rows(10)
                        ->formatStateUsing(fn (mixed $state): string => is_array($state)
                            ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                            : (string) $state)
                        ->columnSpanFull(),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('quotations.fields.code'))
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_code')
                    ->label(__('quotations.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'sent' => 'info',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __("quotations.statuses.{$state}"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_id')
                    ->label(__('quotations.fields.customer'))
                    ->formatStateUsing(function ($state): string {
                        return (string) (DB::table('customers')->where('id', $state)->value('legal_name') ?? 'Cliente non disponibile');
                    })
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->label(__('quotations.fields.total'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_total')
                    ->label(__('quotations.fields.cost_total'))
                    ->money('EUR')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('margin')
                    ->label(__('quotations.margin.label'))
                    ->state(fn (Quotation $record): string => self::marginSummary($record))
                    ->badge()
                    ->color(fn (Quotation $record): string => self::marginColour($record))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('project')
                    ->label(__('quotations.fields.project'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('delivery_at')
                    ->label(__('quotations.fields.delivery_at'))
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('issued_at')
                    ->label(__('quotations.fields.issued_at'))
                    ->dateTime()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
            AttachmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'view' => Pages\ViewQuotation::route('/{record}'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }

    protected static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: self::ACCOUNT_FEATURE_ADMIN,
            operatorFeature: self::ACCOUNT_FEATURE_OPERATOR,
            adminDefault: true,
            operatorDefault: true,
            fallback: false,
        );
    }

    /**
     * Margine in euro e percento, leggibile in lista: quanto resta del
     * ricavo dopo i costi. Sconosciuto finché non c'è un costo caricato.
     */
    public static function marginSummary(?Quotation $quotation): string
    {
        if (! $quotation instanceof Quotation || ! $quotation->exists) {
            return (string) __('quotations.margin.unknown');
        }

        $cost = (float) $quotation->cost_total;
        $revenue = (float) $quotation->taxable_amount > 0.0
            ? (float) $quotation->taxable_amount
            : (float) $quotation->subtotal;

        if ($cost <= 0.0) {
            return (string) __('quotations.margin.unknown');
        }

        $margin = $revenue - $cost;
        $percent = $revenue > 0.0 ? ($margin / $revenue) * 100 : 0.0;

        return sprintf(
            '%s € (%s%%)',
            number_format($margin, 2, ',', '.'),
            number_format($percent, 1, ',', '.'),
        );
    }

    /**
     * Ambra sotto il 15%% di margine, rosso in perdita: un lavoro venduto
     * sotto costo deve urlare dalla lista.
     */
    public static function marginColour(Quotation $quotation): string
    {
        $cost = (float) $quotation->cost_total;
        $revenue = (float) $quotation->taxable_amount > 0.0
            ? (float) $quotation->taxable_amount
            : (float) $quotation->subtotal;

        if ($cost <= 0.0 || $revenue <= 0.0) {
            return 'gray';
        }

        $percent = (($revenue - $cost) / $revenue) * 100;

        return match (true) {
            $percent < 0 => 'danger',
            $percent < 15 => 'warning',
            default => 'success',
        };
    }
}
