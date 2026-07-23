<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Modules\Quotation\Filament\Resources\QuotationResource\Pages;
use Modules\Quotation\Filament\Resources\QuotationResource\RelationManagers\AttachmentsRelationManager;
use Modules\Quotation\Filament\Resources\QuotationResource\RelationManagers\ItemsRelationManager;
use Modules\Quotation\Models\Quotation;

final class QuotationResource extends Resource
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
    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        /** @var \Modules\Quotation\Models\Quotation $record */
        return array_filter([
            __('quotations.fields.customer') => $record->customer?->legal_name,
            __('quotations.fields.status') => $record->status_code ? __('quotations.statuses.'.$record->status_code) : null,
        ]);
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    private const ACCOUNT_FEATURE_ADMIN = 'admin.quotations';

    private const ACCOUNT_FEATURE_OPERATOR = 'operator.quotations';

    public static function getNavigationGroup(): ?string
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

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
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
                            TextInput::make('vat_number')->label('P.IVA')->maxLength(255),
                            TextInput::make('city')->label(__('quotations.voice.city'))->maxLength(255),
                            TextInput::make('email')->label('Email')->email()->maxLength(255),
                            TextInput::make('phone')->label(__('quotations.voice.phone'))->maxLength(255),
                        ])
                        ->createOptionUsing(fn (array $data): int => (int) \Modules\Customer\Models\Customer::query()->create([
                            'legal_name' => $data['legal_name'],
                            'vat_number' => $data['vat_number'] ?? null,
                            'city' => $data['city'] ?? null,
                            'email' => $data['email'] ?? null,
                            'phone' => $data['phone'] ?? null,
                            'is_active' => true,
                        ])->getKey()),

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
                ])
                ->columns(2),

            Section::make(__('quotations.voice.section_title'))
                ->collapsible()
                ->collapsed()
                ->columnSpanFull()
                ->visible(fn ($record): bool => $record !== null
                    && (filled($record->voice_transcript) || filled($record->voice_ai_response)))
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
                            ? (string) json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                            : (string) $state)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('quotations.fields.code'))
                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
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
}
