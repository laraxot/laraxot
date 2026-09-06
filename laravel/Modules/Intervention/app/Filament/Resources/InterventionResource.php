<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\TextColor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use Illuminate\Support\Str;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Document\Filament\Pages\UploadInterventionPhotos;
use Modules\Intervention\Actions\FinalizeInterventionReportAction;
use Modules\Intervention\Actions\StartTimePunchAction;
use Modules\Intervention\Actions\StopTimePunchAction;
use Modules\Intervention\Actions\SyncInterventionNotePhotosAction;
use Modules\Intervention\Actions\SyncInterventionWorkMinutesFromTimePunchesAction;
use Modules\Intervention\Exceptions\NoOpenTimePunchToStop;
use Modules\Intervention\Exceptions\OpenTimePunchAlreadyExists;
use Modules\Intervention\Filament\Resources\InterventionResource\Pages;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\InventoryMovementsRelationManager;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\IssueReportsRelationManager;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\MediaRelationManager;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\SignaturesRelationManager;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\SketchesRelationManager;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\TimePunchesRelationManager;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\Intervention\Models\TimePunch;
use Modules\Intervention\Support\DailyNoteHtml;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\Platform\Settings\SettingsRepository;
use Modules\User\Models\User;

final class InterventionResource extends GatedXotBaseResource
{
    protected static ?string $model = Intervention::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    private static function ensureString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_numeric($value) || is_bool($value) || $value === null) {
            return (string) $value;
        }

        return '';
    }

    /**
     * @return array<string, TextColor>
     */
    public static function richTextColors(): array
    {
        return [
            'slate' => TextColor::make('Grigio', '#475569', darkColor: '#cbd5e1'),
            'blue' => TextColor::make('Blu', '#2563eb', darkColor: '#60a5fa'),
            'green' => TextColor::make('Verde', '#16a34a', darkColor: '#4ade80'),
            'amber' => TextColor::make('Ambra', '#d97706', darkColor: '#fbbf24'),
            'red' => TextColor::make('Rosso', '#dc2626', darkColor: '#f87171'),
            'purple' => TextColor::make('Viola', '#7c3aed', darkColor: '#a78bfa'),
        ];
    }

    public static function notePhotoDirectory(Intervention|string|null $record, mixed $workOrderId): string
    {
        if ($record instanceof Intervention && $record->exists) {
            return "media/interventions/{$record->id}/note-photos";
        }

        $normalizedWorkOrderId = max(0, (int) (is_numeric($workOrderId) ? $workOrderId : 0));

        return "media/interventions/draft/work-order-{$normalizedWorkOrderId}/note-photos";
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function getNavigationSort(): int
    {
        return 2;
    }

    public static function getNavigationGroup(): string
    {
        return __('interventions.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('interventions.intervention');
    }

    public static function getPluralModelLabel(): string
    {
        return __('interventions.interventions');
    }

    /**
     * Stati della decisione sull'extra preventivo.
     *
     * @return array<string, string>
     */
    public static function extraStatusOptions(): array
    {
        return [
            'pending' => __('interventions.extra.statuses.pending'),
            'to_bill' => __('interventions.extra.statuses.to_bill'),
            'waived' => __('interventions.extra.statuses.waived'),
        ];
    }

    public static function extraStatusColor(string $status): string
    {
        return match ($status) {
            'to_bill' => 'success',
            'waived' => 'gray',
            default => 'warning',
        };
    }

    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        Select::make('work_order_id')
                            ->label(__('interventions.fields.work_order_id'))
                            ->options(fn (): array => self::getWorkOrderOptions())
                            ->searchable()
                            ->preload()
                            ->visible(fn (): bool => ! self::isDirectCustomerMode())
                            ->required(fn (): bool => ! self::isDirectCustomerMode()),

                        Select::make('customer_id')
                            ->label(__('customers.customer'))
                            ->options(fn (): array => self::getCustomerOptions())
                            ->searchable()
                            ->preload()
                            ->visible(fn (): bool => self::isDirectCustomerMode())
                            ->required(fn (): bool => self::isDirectCustomerMode())
                            ->helperText('Seleziona il cliente per cui stai eseguendo l\'intervento.'),

                        Select::make('assigned_user_ids')
                            ->label(__('interventions.fields.performed_by'))
                            ->options(fn (): array => self::getAssignableOperatorOptions())
                            ->getOptionLabelsUsing(fn (array $values): array => User::query()
                                ->whereIn('id', $values)
                                ->pluck('name', 'id')
                                ->map(static fn (mixed $name): string => self::ensureString($name))
                                ->all())
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->default(fn (): array => self::shouldForceSelfAssignment() && Auth::id() !== null ? [(int) Auth::id()] : [])
                            ->afterStateHydrated(function (Select $component, ?Intervention $record): void {
                                if (! $record instanceof Intervention) {
                                    return;
                                }

                                // Gli id utente sono UUID: restano stringhe, altrimenti
                                // il cast li appiattisce e la select non ritrova nessuno.
                                $assignedIds = $record->assignedUsers()->pluck('users.id')->map(static fn (mixed $id): string => self::ensureString($id))->all();

                                if ($assignedIds === [] && $record->performed_by_user_id !== null) {
                                    $performedById = $record->performed_by_user_id;
                                    $assignedIds = [self::ensureString($performedById)];
                                }

                                $component->state($assignedIds);
                            })
                            ->disabled(fn (): bool => self::shouldForceSelfAssignment()),

                        Hidden::make('performed_by_user_id')
                            ->default(fn (): ?int => self::shouldForceSelfAssignment() ? (Auth::id() !== null ? (int) Auth::id() : null) : null)
                            ->visible(fn (): bool => self::shouldForceSelfAssignment()),
                    ]),

                    Grid::make(2)->components([
                        DatePicker::make('scheduled_date')
                            ->label(__('interventions.fields.scheduled_at'))
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->dehydrated(fn (): bool => self::isDirectCustomerMode())
                            ->afterStateHydrated(function (DatePicker $component, ?Intervention $record): void {
                                if ($record?->scheduled_at === null) {
                                    return;
                                }

                                $component->state($record->scheduled_at->toDateString());
                            })
                            ->visible(fn (): bool => self::isDirectCustomerMode()),
                        DateTimePicker::make('scheduled_at')
                            ->label(__('interventions.fields.scheduled_at'))
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false)
                            ->visible(fn (): bool => ! self::isDirectCustomerMode()),
                        TextInput::make('amount')
                            ->label(__('interventions.fields.labor_amount'))
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('€')
                            ->live()
                            ->helperText(self::isDirectCustomerMode() ? 'Inserisci l\'importo solo se vuoi far vedere subito il consuntivo economico.' : null),
                    ]),

                    Grid::make(3)->components([
                        TextInput::make('work_hours')
                            ->label(__('interventions.fields.work_hours'))
                            ->numeric()
                            ->minValue(0)
                            ->step(0.25)
                            ->default(0)
                            ->helperText('Le ore sono solo indicative. Il totale si calcola sul campo Manodopera €.')
                            ->afterStateHydrated(function (TextInput $component, ?Intervention $record): void {
                                if (! $record instanceof Intervention) {
                                    return;
                                }

                                $component->state(number_format(((int) ($record->work_minutes ?? 0)) / 60, 2, '.', ''));
                            }),
                        TextInput::make('callout_fee')
                            ->label(__('interventions.fields.callout_fee'))
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('€')
                            ->default(0)
                            ->live()
                            ->helperText('Lascia 0 se non applichi il diritto di chiamata.'),
                        TextEntry::make('total_amount_preview')
                            ->label(__('interventions.fields.total_amount'))
                            ->state(function (callable $get, ?Intervention $record): string {
                                $amountValue = $get('amount') ?? 0;
                                $laborAmount = is_numeric($amountValue) ? (float) $amountValue : 0.0;
                                $calloutFeeValue = $get('callout_fee') ?? 0;
                                $calloutFee = is_numeric($calloutFeeValue) ? (float) $calloutFeeValue : 0.0;
                                $materialsTotal = self::previewMaterialsTotal($get('manual_material_lines'), $record);

                                return number_format($laborAmount + $calloutFee + $materialsTotal, 2, ',', '.').' €';
                            })
                            ->live(),
                    ]),

                    Grid::make(2)->components([
                        DateTimePicker::make('started_at')
                            ->label(__('interventions.fields.started_at'))
                            ->visible(fn (): bool => ! self::isDirectCustomerMode()),
                        DateTimePicker::make('ended_at')
                            ->label(__('interventions.fields.ended_at'))
                            ->visible(fn (): bool => ! self::isDirectCustomerMode()),
                    ]),

                    Grid::make(2)->components([
                        TextInput::make('travel_km')
                            ->label(__('interventions.fields.travel_km'))
                            ->numeric()
                            ->minValue(0)
                            ->visible(fn (): bool => ! self::isDirectCustomerMode()),
                        TextEntry::make('materials_total_preview')
                            ->label(__('interventions.fields.materials_total'))
                            ->state(function (callable $get, ?Intervention $record): string {
                                return number_format(self::previewMaterialsTotal($get('manual_material_lines'), $record), 2, ',', '.').' €';
                            })
                            ->live(),
                    ]),

                    RichEditor::make('notes_html')
                        ->label(__('interventions.fields.notes'))
                        ->columnSpanFull()
                        ->live(debounce: 500)
                        ->toolbarButtons([
                            ['bold', 'italic', 'underline', 'strike', 'link', 'textColor'],
                            ['blockquote', 'bulletList', 'orderedList'],
                            ['undo', 'redo'],
                        ])
                        ->textColors(self::richTextColors())
                        ->placeholder(self::isDirectCustomerMode() ? 'Descrivi cosa hai fatto e, se serve, annota materiali o codici pezzo.' : null)
                        ->helperText('Mentre scrivi compaiono suggerimenti riutilizzabili con un click. Puoi anche allegare foto alla nota dell\'intervento.'),

                    FileUpload::make('notes_photo_paths')
                        ->label(__('interventions.fields.photos'))
                        ->disk('public')
                        ->directory(fn (Get $get, ?Intervention $record): string => self::notePhotoDirectory(
                            record: $record,
                            workOrderId: $get('work_order_id'),
                        ))
                        ->image()
                        ->multiple()
                        ->appendFiles()
                        ->maxParallelUploads(1)
                        ->automaticallyResizeImagesMode('contain')
                        ->automaticallyResizeImagesToWidth('1280')
                        ->automaticallyResizeImagesToHeight('1280')
                        ->extraInputAttributes([
                            'accept' => 'image/*',
                            'capture' => 'environment',
                        ], merge: true)
                        ->afterStateHydrated(function (FileUpload $component, ?Intervention $record): void {
                            if (! $record instanceof Intervention) {
                                return;
                            }

                            $component->state(
                                $record->media()
                                    ->where('kind', 'photo')
                                    ->where('meta->scope', 'intervention_note')
                                    ->orderBy('id')
                                    ->pluck('path')
                                    ->all()
                            );
                        })
                        ->helperText(__('interventions.helpers.daily_note_photos'))
                        ->columnSpanFull(),

                    TextEntry::make('notes_suggestions')
                        ->label(__('interventions.fields.notes_suggestions'))
                        ->columnSpanFull()
                        ->state(function (Get $get): ?HtmlString {
                            $suggestions = self::getPreviousNoteSuggestions(self::normalizeTextState($get('notes_html')));

                            if ($suggestions === []) {
                                return null;
                            }

                            $currentNotes = self::normalizeTextState($get('notes_html'));
                            $buttons = collect($suggestions)
                                ->values()
                                ->map(static function (string $suggestion) use ($currentNotes): string {
                                    $nextValue = self::applyNoteSuggestion($currentNotes, $suggestion);

                                    return sprintf(
                                        '<button type="button" class="text-primary-600 hover:text-primary-500 underline underline-offset-2" x-on:click="$wire.set(\'data.notes_html\', %s)">%s</button>',
                                        e((string) Js::from(DailyNoteHtml::legacyToHtml($nextValue))),
                                        e(Str::limit($suggestion, 90))
                                    );
                                })
                                ->implode(' ');

                            return new HtmlString($buttons);
                        }),

                    Repeater::make('manual_material_lines')
                        ->label(__('interventions.fields.material_lines'))
                        ->columnSpanFull()
                        ->schema([
                            TextInput::make('description')
                                ->label(__('interventions.fields.material_description')),
                            Grid::make(2)->components([
                                TextInput::make('quantity')
                                    ->label(__('interventions.fields.material_quantity'))
                                    ->numeric()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->default(1)
                                    ->live(),
                                Select::make('unit')
                                    ->label(__('interventions.fields.material_unit'))
                                    ->options([
                                        'pz' => 'Pz',
                                        'mt' => 'Mt',
                                    ])
                                    ->default('pz'),
                            ]),
                            TextInput::make('unit_price')
                                ->label(__('interventions.fields.material_unit_price'))
                                ->numeric()
                                ->minValue(0)
                                ->step(0.01)
                                ->prefix('€')
                                ->default(0)
                                ->live(),
                            TextEntry::make('line_total_preview')
                                ->label(__('interventions.fields.material_line_total'))
                                ->state(function (callable $get): string {
                                    $qtyValue = $get('quantity') ?? 0;
                                    $quantity = is_numeric($qtyValue) ? (float) $qtyValue : 0.0;
                                    $priceValue = $get('unit_price') ?? 0;
                                    $unitPrice = is_numeric($priceValue) ? (float) $priceValue : 0.0;

                                    return number_format($quantity * $unitPrice, 2, ',', '.').' €';
                                })
                                ->live(),

                            // L'extra e' calcolato al salvataggio confrontando la riga
                            // con i preventivi della commessa: qui si legge soltanto.
                            TextEntry::make('extra_preview')
                                ->label(__('interventions.extra.amount'))
                                ->state(function (callable $get): string {
                                    $extraQtyValue = $get('extra_quantity');
                                    $extraQuantity = is_numeric($extraQtyValue) ? (float) $extraQtyValue : 0.0;

                                    if ($extraQuantity <= 0.0) {
                                        return __('interventions.extra.none');
                                    }

                                    $extraAmountValue = $get('extra_amount');
                                    $extraAmount = is_numeric($extraAmountValue) ? (float) $extraAmountValue : 0.0;

                                    return sprintf(
                                        '%s × — %s €',
                                        number_format($extraQuantity, 2, ',', '.'),
                                        number_format($extraAmount, 2, ',', '.'),
                                    );
                                })
                                ->helperText(__('interventions.extra.computed_hint')),

                            Select::make('extra_status_code')
                                ->label(__('interventions.extra.status'))
                                ->options(self::extraStatusOptions())
                                ->default('pending')
                                ->selectablePlaceholder(false)
                                ->helperText(__('interventions.extra.hint'))
                                ->visible(fn (callable $get): bool => (is_numeric($get('extra_quantity')) ? (float) $get('extra_quantity') : 0.0) > 0.0),

                            Hidden::make('extra_quantity'),
                            Hidden::make('extra_amount'),
                        ])
                        ->default(fn (?Intervention $record): array => self::materialLinesFormState($record))
                        // `manual_material_lines` non è una colonna: il fill da record
                        // esistente lo lascia vuoto (il default vale solo in create).
                        // Senza questo il repeater si apriva vuoto in edit e, poiché il
                        // salvataggio ricostruisce le righe da zero (materialLines()->delete()),
                        // aprire e salvare un intervento cancellava le righe appena scritte
                        // dal rapportino vocale.
                        ->afterStateHydrated(function (Repeater $component, ?Intervention $record): void {
                            if (! $record instanceof Intervention) {
                                return;
                            }

                            $component->state(self::materialLinesFormState($record));
                        })
                        ->dehydrated(false)
                        ->reorderable(false)
                        ->addActionLabel('Aggiungi materiale')
                        ->columns(4),
                ])
                ->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('work_order_id')
                    ->label(__('interventions.fields.work_order_id'))
                    ->formatStateUsing(function (mixed $state, Intervention $record): string {
                        $stateValue = is_numeric($state) ? (int) $state : 0;
                        if ($stateValue === 0) {
                            return '—';
                        }
                        $wo = DB::table('work_orders')->where('id', $stateValue)->first(['title', 'code']);

                        $result = $wo?->title ?: $wo?->code ?: '—';

                        return self::ensureString($result);
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_display')
                    ->label(__('work_orders.fields.customer'))
                    ->state(fn (Intervention $record): string => $record->workOrder->customer->legal_name
                        ?? $record->customer->legal_name
                        ?? '—')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $q) use ($search): void {
                            $q->whereHas('workOrder.customer', fn (Builder $wq): Builder => $wq->where('legal_name', 'like', "%{$search}%"))
                                ->orWhereHas('customer', fn (Builder $cq): Builder => $cq->where('legal_name', 'like', "%{$search}%"));
                        });
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('workOrder.site.name')
                    ->label(__('work_orders.fields.site'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('performedBy.name')
                    ->label(__('interventions.fields.performed_by'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assigned_users_summary')
                    ->label(__('interventions.fields.operators'))
                    ->state(function (Intervention $record): string {
                        $names = $record->assignedUsers()
                            ->orderBy('name')
                            ->get()
                            ->map(fn (User $user): string => $user->name)
                            ->all();

                        if ($names === [] && $record->performedBy !== null) {
                            $names = [$record->performedBy->name];
                        }

                        return implode(', ', $names);
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->label(__('interventions.fields.started_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ended_at')
                    ->label(__('interventions.fields.ended_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('interventions.fields.scheduled_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('interventions.fields.labor_amount'))
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('callout_fee')
                    ->label(__('interventions.fields.callout_fee'))
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('materials_total')
                    ->label(__('interventions.fields.materials_total'))
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label(__('interventions.fields.total_amount'))
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                // Extra fuori preventivo: se resta invisibile, viene regalato.
                Tables\Columns\TextColumn::make('extra_total')
                    ->label(__('interventions.extra.total'))
                    ->state(fn (Intervention $record): float => round(
                        (float) $record->materialLines()->sum('extra_amount'),
                        2,
                    ))
                    ->money('EUR')
                    ->badge()
                    ->color(fn (mixed $state): string => (is_numeric($state) ? (float) $state : 0.0) > 0.0 ? 'warning' : 'gray'),
                Tables\Columns\TextColumn::make('work_minutes')
                    ->label(__('interventions.fields.work_minutes'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('outcome_code')
                    ->label(__('interventions.fields.outcome'))
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'completed' => __('interventions.outcomes.completed'),
                        'suspended' => __('interventions.outcomes.suspended'),
                        'quotation' => __('interventions.outcomes.quotation'),
                        default => $state ?: '—',
                    })
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label(__('interventions.fields.notes'))
                    ->limit(70)
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('travel_km')
                    ->label(__('interventions.fields.travel_km'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('has_extra')
                    ->label(__('interventions.extra.has_extra'))
                    ->query(fn (Builder $query): Builder => $query->whereHas(
                        'materialLines',
                        fn (Builder $lines): Builder => $lines->where('extra_quantity', '>', 0),
                    )),
                SelectFilter::make('customer_id')
                    ->label(__('work_orders.fields.customer'))
                    ->options(fn (): array => Customer::query()->whereNotNull('legal_name')->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;
                        $customerId = is_numeric($value) ? (int) $value : 0;

                        return $customerId > 0
                            ? $query->where(function (Builder $q) use ($customerId): void {
                                $q->whereHas('workOrder', fn (Builder $wq): Builder => $wq->where('customer_id', $customerId))
                                    ->orWhere('customer_id', $customerId);
                            })
                            : $query;
                    }),
                SelectFilter::make('customer_site_id')
                    ->label(__('work_orders.fields.site'))
                    ->options(fn (): array => CustomerSite::query()->whereNotNull('name')->orderBy('name')->pluck('name', 'id')->all())
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;
                        $siteId = is_numeric($value) ? (int) $value : 0;

                        return $siteId > 0
                            ? $query->whereHas('workOrder', fn (Builder $workOrderQuery): Builder => $workOrderQuery->where('customer_site_id', $siteId))
                            : $query;
                    }),
                SelectFilter::make('outcome_code')
                    ->label(__('interventions.fields.outcome'))
                    ->options([
                        'completed' => __('interventions.outcomes.completed'),
                        'suspended' => __('interventions.outcomes.suspended'),
                        'quotation' => __('interventions.outcomes.quotation'),
                    ]),
                Filter::make('scheduled_between')
                    ->label(__('work_orders.fields.report_from').' / '.__('work_orders.fields.report_to'))
                    ->schema([
                        DatePicker::make('from')
                            ->label(__('work_orders.fields.report_from')),
                        DatePicker::make('to')
                            ->label(__('work_orders.fields.report_to')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $from = $data['from'] ?? null;
                        $to = $data['to'] ?? null;

                        return $query
                            ->when(
                                filled($from),
                                fn (Builder $innerQuery): Builder => $innerQuery->whereDate('scheduled_at', '>=', self::ensureString($from)),
                            )
                            ->when(
                                filled($to),
                                fn (Builder $innerQuery): Builder => $innerQuery->whereDate('scheduled_at', '<=', self::ensureString($to)),
                            );
                    }),
            ])
            ->recordActions([
                Action::make('start_time_punch')
                    ->label(__('interventions.actions.start_time_punch'))
                    ->icon('heroicon-o-play')
                    ->visible(function (Intervention $record): bool {
                        $userId = Auth::id();
                        if ($userId === null) {
                            return false;
                        }
                        if (! self::isAssignedToUser($record, (string) $userId)) {
                            return false;
                        }

                        $hasOpenOnThis = TimePunch::query()
                            ->where('intervention_id', $record->id)
                            ->where('user_id', $userId)
                            ->whereNull('ended_at')
                            ->exists();

                        return ! $hasOpenOnThis;
                    })
                    ->action(function (Intervention $record): void {
                        $userId = (int) Auth::id();
                        try {
                            app(StartTimePunchAction::class)->execute($record, $userId);
                        } catch (OpenTimePunchAlreadyExists $e) {
                            Notification::make()
                                ->title($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Action::make('stop_time_punch')
                    ->label(__('interventions.actions.stop_time_punch'))
                    ->icon('heroicon-o-stop')
                    ->color('danger')
                    ->visible(function (Intervention $record): bool {
                        $userId = Auth::id();
                        if ($userId === null) {
                            return false;
                        }
                        if (! self::isAssignedToUser($record, (string) $userId)) {
                            return false;
                        }

                        return TimePunch::query()
                            ->where('intervention_id', $record->id)
                            ->where('user_id', $userId)
                            ->whereNull('ended_at')
                            ->exists();
                    })
                    ->requiresConfirmation()
                    ->action(function (Intervention $record): void {
                        $userId = (int) Auth::id();
                        try {
                            app(StopTimePunchAction::class)->execute($record, $userId);
                            app(SyncInterventionWorkMinutesFromTimePunchesAction::class)->execute($record->refresh());
                        } catch (NoOpenTimePunchToStop $e) {
                            Notification::make()
                                ->title($e->getMessage())
                                ->warning()
                                ->send();
                        }
                    }),

                Action::make('validate')
                    ->label(__('interventions.actions.validate'))
                    ->icon('heroicon-o-check-circle')
                    ->visible(function (Intervention $record): bool {
                        $userId = Auth::id();
                        if ($userId === null) {
                            return false;
                        }

                        return self::isAssignedToUser($record, (string) $userId);
                    })
                    ->schema([
                        TextInput::make('travel_km')
                            ->label(__('interventions.fields.travel_km'))
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->default(fn (Intervention $record): int => (int) ($record->travel_km ?? 0)),
                        TextInput::make('travel_minutes')
                            ->label(__('interventions.fields.travel_minutes'))
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->default(fn (Intervention $record): int => (int) ($record->travel_minutes ?? 0)),
                        Textarea::make('notes')
                            ->label(__('interventions.fields.notes'))
                            ->default(fn (Intervention $record): ?string => $record->notes)
                            ->columnSpanFull(),
                        Select::make('outcome_code')
                            ->label(__('interventions.fields.outcome'))
                            ->options([
                                'completed' => __('interventions.outcomes.completed'),
                                'suspended' => __('interventions.outcomes.suspended'),
                                'quotation' => __('interventions.outcomes.quotation'),
                            ])
                            ->required()
                            ->default('completed'),
                    ])
                    ->action(function (Intervention $record, array $data): void {
                        $userId = (int) Auth::id();
                        $outcomeValue = $data['outcome_code'] ?? 'completed';
                        $outcomeCode = self::ensureString($outcomeValue);
                        if (! in_array($outcomeCode, ['completed', 'suspended', 'quotation'], true)) {
                            $outcomeCode = 'completed';
                        }

                        $travelKmValue = $data['travel_km'] ?? 0;
                        $travelKm = is_numeric($travelKmValue) ? (int) $travelKmValue : 0;
                        $travelMinutesValue = $data['travel_minutes'] ?? 0;
                        $travelMinutes = is_numeric($travelMinutesValue) ? (int) $travelMinutesValue : 0;

                        app(FinalizeInterventionReportAction::class)->execute(
                            intervention: $record,
                            validatedByUserId: $userId,
                            travelKm: $travelKm,
                            travelMinutes: $travelMinutes,
                            notes: isset($data['notes']) ? (is_string($data['notes']) ? $data['notes'] : null) : null,
                            outcomeCode: $outcomeCode,
                        );
                    }),

                Action::make('upload_photos')
                    ->label(__('documents.actions.upload_photos'))
                    ->icon('heroicon-o-camera')
                    ->visible(function (Intervention $record): bool {
                        if (! request()->is('operator/*')) {
                            return false;
                        }

                        $userId = Auth::id();
                        if ($userId === null) {
                            return false;
                        }

                        return self::isAssignedToUser($record, (string) $userId);
                    })
                    ->url(fn (Intervention $record): string => UploadInterventionPhotos::getUrl([
                        'interventionId' => $record->id,
                    ])),
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn (): bool => ! request()->is('operator/*')),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->visible(fn (): bool => ! request()->is('operator/*')),
                RestoreBulkAction::make()
                    ->visible(fn (): bool => ! request()->is('operator/*')),
                ForceDeleteBulkAction::make()
                    ->visible(fn (): bool => ! request()->is('operator/*')),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        /** @var User|null $user */
        $user = Auth::user();

        if (request()->is('operator/*') && ! (bool) config('app.demo_mode')) {
            $userId = Auth::id();
            if ($userId !== null) {
                $query->where(function (Builder $innerQuery) use ($userId): void {
                    $innerQuery
                        ->where('performed_by_user_id', $userId)
                        ->orWhereExists(function (QueryBuilder $subQuery) use ($userId): void {
                            $subQuery->selectRaw('1')
                                ->from('intervention_user_assignments')
                                ->whereColumn('intervention_user_assignments.intervention_id', 'interventions.id')
                                ->where('intervention_user_assignments.user_id', $userId);
                        });
                });
            } else {
                $query->whereRaw('1=0');
            }
        }

        if (
            request()->is('admin/*')
            && $user instanceof User
            && AccountFeatures::hasAssignedPerimeter($user)
            && AccountFeatures::hasFeature($user, 'admin.team_interventions', true)
        ) {
            AccountFeatures::scopeAssignedInterventions($query, $user);
        }

        return $query;
    }

    public static function isDirectCustomerMode(): bool
    {
        return app(SettingsRepository::class)->getString('interventions.work_mode', 'work_order') === 'customer';
    }

    private static function shouldForceSelfAssignment(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && AccountFeatures::hasAssignedPerimeter($user)
            && AccountFeatures::hasAccountFeature($user, 'operator.force_self_assignment')
            && request()->is('operator/*');
    }

    /**
     * @return array<int, string>
     */
    private static function getWorkOrderOptions(): array
    {
        $query = DB::table('work_orders')
            ->leftJoin('customers', 'customers.id', '=', 'work_orders.customer_id')
            ->leftJoin('customer_sites', 'customer_sites.id', '=', 'work_orders.customer_site_id')
            ->select([
                'work_orders.id',
                'work_orders.code',
                'work_orders.title',
                'customers.legal_name as customer_name',
                'customer_sites.name as site_name',
            ])
            ->orderByDesc('work_orders.id')
            ->limit(100);

        if (request()->is('operator/*') && ! (bool) config('app.demo_mode')) {
            $userId = Auth::id();

            if ($userId === null) {
                return [];
            }

            $query->join('work_order_assignments', 'work_order_assignments.work_order_id', '=', 'work_orders.id')
                ->where('work_order_assignments.user_id', $userId);
        }

        /** @var User|null $user */
        $user = Auth::user();
        if (
            request()->is('admin/*')
            && $user instanceof User
            && AccountFeatures::hasAssignedPerimeter($user)
            && AccountFeatures::hasFeature($user, 'admin.team_interventions', true)
        ) {
            $query->whereExists(function (QueryBuilder $subQuery) use ($user): void {
                $subQuery->selectRaw('1')
                    ->from('work_order_assignments')
                    ->whereColumn('work_order_assignments.work_order_id', 'work_orders.id')
                    ->where('work_order_assignments.user_id', $user->id);
            });
        }

        return $query
            ->get()
            ->mapWithKeys(fn ($row): array => [
                is_numeric($row->id) ? (int) $row->id : 0 => self::formatWorkOrderOptionLabel($row),
            ])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private static function getCustomerOptions(): array
    {
        $query = DB::table('customers')
            ->select(['id', 'legal_name'])
            ->where('is_active', true)
            ->whereNotNull('legal_name')
            ->orderBy('legal_name');

        /** @var User|null $user */
        $user = Auth::user();
        if ($user instanceof User && AccountFeatures::hasAssignedPerimeter($user)) {
            $query->whereExists(function (QueryBuilder $subQuery) use ($user): void {
                $subQuery->selectRaw('1')
                    ->from('work_orders')
                    ->join('work_order_assignments', 'work_order_assignments.work_order_id', '=', 'work_orders.id')
                    ->whereColumn('work_orders.customer_id', 'customers.id')
                    ->where('work_order_assignments.user_id', $user->id);
            });
        }

        return $query
            ->get()
            ->mapWithKeys(fn ($row): array => [
                is_numeric($row->id) ? (int) $row->id : 0 => self::ensureString($row->legal_name),
            ])
            ->all();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function normalizeFormData(array $data): array
    {
        $assignedUserIds = collect(is_array($data['assigned_user_ids'] ?? null) ? $data['assigned_user_ids'] : [])
            ->map(static function (mixed $id): int {
                if (is_int($id)) {
                    return $id;
                }

                return is_numeric($id) ? (int) $id : 0;
            })
            ->filter(static fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();

        if ($assignedUserIds === [] && Auth::id() !== null) {
            $authId = Auth::id();
            $assignedUserIds = [(int) $authId];
        }

        $workHoursValue = $data['work_hours'] ?? 0;
        $workHours = is_numeric($workHoursValue) ? (float) $workHoursValue : 0.0;
        $workMinutes = max(0, (int) round($workHours * 60));

        $scheduledDate = $data['scheduled_date'] ?? null;
        unset($data['scheduled_date']);

        $scheduledAt = $data['scheduled_at'] ?? null;
        if (self::isDirectCustomerMode() && is_string($scheduledDate) && trim($scheduledDate) !== '') {
            $data['scheduled_at'] = trim($scheduledDate).' 08:00:00';
        } elseif (is_string($scheduledAt) && trim($scheduledAt) !== '') {
            $data['scheduled_at'] = $scheduledAt;
        }

        $notesHtmlValue = $data['notes_html'] ?? '';
        $notesHtml = DailyNoteHtml::sanitize(self::ensureString($notesHtmlValue));
        $notesValue = $data['notes'] ?? '';
        $notes = trim(self::ensureString($notesValue));

        if ($notesHtml !== '') {
            $data['notes_html'] = $notesHtml;
            $data['notes'] = DailyNoteHtml::toPlainText($notesHtml);
        } elseif ($notes !== '') {
            $data['notes'] = $notes;
            $data['notes_html'] = DailyNoteHtml::sanitize(DailyNoteHtml::legacyToHtml($notes));
        } else {
            $data['notes'] = null;
            $data['notes_html'] = null;
        }

        $data['assigned_user_ids'] = $assignedUserIds;
        $data['performed_by_user_id'] = $assignedUserIds[0] ?? null;
        $data['work_minutes'] = $workMinutes;
        $amountValue = $data['amount'] ?? 0;
        $calloutFeeValue = $data['callout_fee'] ?? 0;
        $data['amount'] = round(is_numeric($amountValue) ? (float) $amountValue : 0.0, 2);
        $data['callout_fee'] = round(is_numeric($calloutFeeValue) ? (float) $calloutFeeValue : 0.0, 2);
        $data['materials_total'] = round(self::computeManualMaterialsTotal($data['manual_material_lines'] ?? []), 2);
        $data['total_amount'] = round((float) $data['amount'] + (float) $data['callout_fee'] + (float) $data['materials_total'], 2);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function extractPersistableData(array $data): array
    {
        /** @var array<string, mixed> $persisted */
        $persisted = Arr::only($data, [
            'work_order_id',
            'customer_id',
            'performed_by_user_id',
            'scheduled_at',
            'started_at',
            'ended_at',
            'travel_km',
            'travel_minutes',
            'work_minutes',
            'notes',
            'notes_html',
            'outcome_code',
            'amount',
            'callout_fee',
            'materials_total',
            'total_amount',
        ]);

        return $persisted;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function syncSupplementalData(Intervention $record, array $data): void
    {
        $assignedUserIds = collect(is_array($data['assigned_user_ids'] ?? null) ? $data['assigned_user_ids'] : [])
            ->map(static function (mixed $id): int {
                return is_numeric($id) ? (int) $id : 0;
            })
            ->filter(static fn (int $id): bool => $id > 0)
            ->unique()
            ->values()
            ->all();

        $record->assignedUsers()->sync($assignedUserIds);

        $manualLines = collect(is_array($data['manual_material_lines'] ?? null) ? $data['manual_material_lines'] : [])
            ->map(static function (mixed $line): array {
                if (! is_array($line)) {
                    return [
                        'description' => '',
                        'quantity' => 0.0,
                        'unit' => 'pz',
                        'unit_price' => 0.0,
                        'line_total' => 0.0,
                        'extra_status_code' => 'pending',
                    ];
                }

                $descValue = $line['description'] ?? '';
                $description = trim(self::ensureString($descValue));
                $qtyValue = $line['quantity'] ?? 0;
                $quantity = round(is_numeric($qtyValue) ? (float) $qtyValue : 0.0, 2);
                $priceValue = $line['unit_price'] ?? 0;
                $unitPrice = round(is_numeric($priceValue) ? (float) $priceValue : 0.0, 2);
                $unitValue = $line['unit'] ?? 'pz';
                $unit = trim(self::ensureString($unitValue));

                // Le righe vengono ricreate da zero a ogni salvataggio: senza
                // riportare la decisione presa sull'extra, ogni modifica
                // all'intervento la riazzererebbe a "da decidere" e il
                // materiale gia' destinato alla fattura tornerebbe regalabile.
                $extraStatusValue = $line['extra_status_code'] ?? 'pending';
                $extraStatus = trim(self::ensureString($extraStatusValue));

                return [
                    'description' => $description,
                    'quantity' => $quantity,
                    'unit' => $unit !== '' ? $unit : 'pz',
                    'unit_price' => $unitPrice,
                    'line_total' => round($quantity * $unitPrice, 2),
                    'extra_status_code' => array_key_exists($extraStatus, self::extraStatusOptions())
                        ? $extraStatus
                        : 'pending',
                ];
            })
            ->filter(static fn (array $line): bool => $line['description'] !== '' && $line['quantity'] > 0)
            ->values();

        $record->materialLines()->delete();

        if ($manualLines->isNotEmpty()) {
            $record->materialLines()->createMany($manualLines->all());
        }

        app(SyncInterventionNotePhotosAction::class)->execute(
            intervention: $record,
            paths: array_values(array_map(static function (mixed $path): string {
                if (is_string($path)) {
                    return $path;
                }

                return self::ensureString($path);
            }, is_array($data['notes_photo_paths'] ?? null) ? $data['notes_photo_paths'] : [])),
            uploadedByUserId: Auth::id() !== null ? (int) Auth::id() : null,
        );

        $record->refreshEconomicTotals();
    }

    public static function getRelations(): array
    {
        $relations = [
            TimePunchesRelationManager::class,
            IssueReportsRelationManager::class,
            MediaRelationManager::class,
            SignaturesRelationManager::class,
            SketchesRelationManager::class,
        ];

        if (
            app(SettingsRepository::class)->getBool('inventory.allow_link_interventions', true)
            && AccountFeatures::currentPanelAllowsFeature(
                Auth::user(),
                adminFeature: 'admin.interventions',
                operatorFeature: 'operator.materials',
                adminDefault: true,
                operatorDefault: true,
                fallback: true,
            )
        ) {
            array_splice($relations, 2, 0, [InventoryMovementsRelationManager::class]);
        }

        return $relations;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInterventions::route('/'),
            'create' => Pages\CreateIntervention::route('/create'),
            'view' => Pages\ViewIntervention::route('/{record}'),
            'edit' => Pages\EditIntervention::route('/{record}/edit'),
        ];
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.interventions',
            operatorFeature: 'operator.interventions',
            adminDefault: true,
            operatorDefault: true,
            fallback: ! request()->is('customer/*') && ! request()->is('supplier/*'),
        );
    }

    private static function formatWorkOrderOptionLabel(object $row): string
    {
        $customer = trim(self::ensureString($row->customer_name ?? null));
        $site = trim(self::ensureString($row->site_name ?? null));
        $title = trim(self::ensureString($row->title ?? null));
        $code = trim(self::ensureString($row->code ?? null));

        $mainLabel = $site !== '' ? $site : ($title !== '' ? $title : ($code !== '' ? $code : 'Commessa non disponibile'));
        $suffix = $customer !== '' ? $customer : ($code !== '' ? $code : '');

        return $suffix !== '' ? $mainLabel.' - '.$suffix : $mainLabel;
    }

    /**
     * @return array<int, string>
     */
    private static function getAssignableOperatorOptions(): array
    {
        $modelHasRolesTable = self::ensureString(config('permission.table_names.model_has_roles', 'model_has_roles'));
        $rolesTable = self::ensureString(config('permission.table_names.roles', 'roles'));
        $userModel = self::ensureString(config('auth.providers.users.model', User::class));

        return DB::table('users')
            ->whereExists(function (QueryBuilder $query) use ($modelHasRolesTable, $rolesTable, $userModel): void {
                $query->selectRaw('1')
                    ->from($modelHasRolesTable)
                    ->join($rolesTable, $rolesTable.'.id', '=', $modelHasRolesTable.'.role_id')
                    ->whereColumn($modelHasRolesTable.'.model_id', 'users.id')
                    ->where($modelHasRolesTable.'.model_type', $userModel)
                    ->whereIn($rolesTable.'.name', ['operator', 'admin', 'administrator', 'super_admin']);
            })
            ->orderBy('name')
            ->pluck('name', 'id')
            ->mapWithKeys(static fn (mixed $name, mixed $id): array => [
                is_numeric($id) ? (int) $id : 0 => is_string($name) ? $name : self::ensureString($name),
            ])
            ->all();
    }

    /**
     * The material lines of an intervention, in the shape the repeater expects.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function materialLinesFormState(?Intervention $record): array
    {
        if (! $record instanceof Intervention) {
            return [];
        }

        return $record->materialLines()
            ->orderBy('id')
            ->get(['description', 'quantity', 'unit', 'unit_price', 'extra_quantity', 'extra_amount', 'extra_status_code'])
            ->map(static fn (InterventionMaterialLine $line): array => [
                'description' => (string) $line->description,
                'quantity' => (float) $line->quantity,
                'unit' => (string) ($line->unit ?? 'pz'),
                'unit_price' => (float) $line->unit_price,
                'extra_quantity' => (float) $line->extra_quantity,
                'extra_amount' => (float) $line->extra_amount,
                'extra_status_code' => (string) ($line->extra_status_code ?? 'pending'),
            ])
            ->all();
    }

    /**
     * Il materiale di un intervento sta in due posti: le righe scritte a mano
     * nel form e gli scarichi di magazzino. L'anteprima deve mostrare il più
     * alto dei due totali (stessa regola di refreshEconomicTotals), non la
     * somma: sono modi alternativi di registrare lo stesso materiale.
     */
    private static function previewMaterialsTotal(mixed $lines, ?Intervention $record): float
    {
        $manualTotal = self::computeManualMaterialsTotal($lines);

        if (! $record instanceof Intervention || $record->getKey() === null) {
            return $manualTotal;
        }

        $rawWarehouseTotal = $record->inventoryMovements()
            ->where('movement_type', 'scarico')
            ->sum('line_total');

        $warehouseTotal = is_numeric($rawWarehouseTotal) ? (float) $rawWarehouseTotal : 0.0;

        return round(max($manualTotal, $warehouseTotal), 2);
    }

    private static function computeManualMaterialsTotal(mixed $lines): float
    {
        if (! is_iterable($lines)) {
            return 0.0;
        }

        $total = 0.0;

        foreach ($lines as $line) {
            if (! is_array($line)) {
                continue;
            }

            $qtyValue = $line['quantity'] ?? 0;
            $quantity = round((float) (is_numeric($qtyValue) ? $qtyValue : 0), 2);
            $priceValue = $line['unit_price'] ?? 0;
            $unitPrice = round((float) (is_numeric($priceValue) ? $priceValue : 0), 2);
            $descValue = $line['description'] ?? '';
            $description = trim(self::ensureString($descValue));

            if ($description === '' || $quantity <= 0) {
                continue;
            }

            $total += $quantity * $unitPrice;
        }

        return round($total, 2);
    }

    /**
     * @return array<int, string>
     */
    private static function getPreviousNoteSuggestions(string $currentValue = ''): array
    {
        $query = DB::table('interventions')
            ->whereNotNull('notes')
            ->where('notes', '!=', '');

        $currentValue = trim($currentValue);
        if ($currentValue !== '') {
            $query->where('notes', 'like', '%'.$currentValue.'%');
        }

        return $query
            ->orderByDesc('id')
            ->limit(5)
            ->pluck('notes')
            ->map(static fn ($note): string => trim(self::ensureString($note)))
            ->filter(static fn (string $note): bool => $note !== '')
            ->unique()
            ->values()
            ->all();
    }

    private static function applyNoteSuggestion(string $currentValue, string $suggestion): string
    {
        $currentValue = trim($currentValue);
        $suggestion = trim($suggestion);

        if ($suggestion === '') {
            return $currentValue;
        }

        if ($currentValue === '') {
            return $suggestion;
        }

        $normalizedCurrentValue = Str::lower($currentValue);
        $normalizedSuggestion = Str::lower($suggestion);

        if (Str::contains($normalizedSuggestion, $normalizedCurrentValue)) {
            return $suggestion;
        }

        if (Str::contains($normalizedCurrentValue, $normalizedSuggestion)) {
            return $currentValue;
        }

        return $currentValue.PHP_EOL.$suggestion;
    }

    private static function normalizeTextState(mixed $value): string
    {
        if (is_string($value)) {
            return DailyNoteHtml::toPlainText($value);
        }

        if (is_numeric($value)) {
            return (string) $value;
        }

        return '';
    }

    public static function isAssignedToUser(Intervention $record, int|string $userId): bool
    {
        $performedById = $record->performed_by_user_id;
        if ($performedById !== null && self::ensureString($performedById) === (string) $userId) {
            return true;
        }

        return $record->assignedUsers()
            ->where('users.id', $userId)
            ->exists();
    }
}
