<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Billing\Actions\BuildWorkOrderPreInvoiceAction;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\User\Models\User;
use Modules\WorkOrder\Actions\ComputeWorkOrderEffortAction;
use Modules\WorkOrder\Actions\GenerateWorkOrderReportAction;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\Pages;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\ActivitiesRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\AssignmentsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\ChangeOrdersRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\ComplianceDocumentsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\InterventionsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\MaterialsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\PublicProcurementContractsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\ReadinessItemsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\StatusEventsRelationManager;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;

final class WorkOrderResource extends GatedXotBaseResource
{
    protected static ?string $model = WorkOrder::class;

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
        return ['code', 'title', 'description', 'customer.legal_name'];
    }

    /**
     * @return array<string, string|null>
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var WorkOrder $record */

        return array_filter([
            __('workorder::work_orders.fields.customer') => $record->customer?->legal_name !== null
                ? (string) $record->customer->legal_name
                : null,
            __('workorder::work_orders.fields.title') => $record->title,
        ], static fn (?string $value): bool => $value !== null);
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

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

    public static function getNavigationSort(): int
    {
        return 3;
    }

    public static function getNavigationGroup(): string
    {
        return __('workorder::work_orders.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('workorder::work_orders.work_order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('workorder::work_orders.work_orders');
    }

    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('code')
                            ->maxLength(255),

                        Select::make('status_id')
                            ->options(fn () => WorkOrderStatus::query()->whereNotNull('name')->orderBy('sort_order')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload(),
                    ]),

                    Select::make('customer_id')
                        ->options(fn () => self::getCustomerOptionsForCurrentPanel())
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function (callable $set): void {
                            $set('customer_site_id', null);
                            $set('primary_contact_id', null);
                        })
                        ->required(),

                    Grid::make(2)->components([
                        Select::make('customer_site_id')
                            ->options(function (callable $get): array {
                                $customerId = is_int($get('customer_id')) ? (int) $get('customer_id') : 0;
                                if ($customerId <= 0) {
                                    return [];
                                }

                                return CustomerSite::query()
                                    ->where('customer_id', $customerId)
                                    ->whereNotNull('name')
                                    ->orderBy('id')
                                    ->pluck('name', 'id')
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->disabled(fn (callable $get): bool => blank($get('customer_id')))
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('primary_contact_id', null)),

                        Select::make('primary_contact_id')
                            ->options(function (callable $get): array {
                                $customerId = is_int($get('customer_id')) ? (int) $get('customer_id') : 0;
                                if ($customerId <= 0) {
                                    return [];
                                }

                                $siteId = is_int($get('customer_site_id')) ? (int) $get('customer_site_id') : 0;

                                return Contact::query()
                                    ->where('customer_id', $customerId)
                                    ->when($siteId > 0, fn ($q) => $q->where('customer_site_id', $siteId))
                                    ->orderBy('full_name')
                                    ->pluck('full_name', 'id')
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->disabled(fn (callable $get): bool => blank($get('customer_id'))),
                    ]),

                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),

                    Textarea::make('description')
                        ->columnSpanFull(),

                    Textarea::make('notes')
                        ->columnSpanFull(),

                    Grid::make(2)->components([
                        DateTimePicker::make('opened_at'),
                        DateTimePicker::make('scheduled_at'),
                        DateTimePicker::make('delivery_due_at')
                            ->label(__('workorder::work_orders.fields.delivery_due_at'))
                            ->helperText(__('workorder::work_orders.fields.delivery_due_at_hint')),
                        DateTimePicker::make('started_at'),
                        DateTimePicker::make('completed_at'),
                    ]),

                    Grid::make(2)->components([
                        TextInput::make('planned_minutes')
                            ->helperText(__('workorder::work_orders.effort.hint'))
                            ->numeric()
                            ->minValue(0)
                            ->step(1),
                        TextInput::make('progress_percent')
                            ->label(__('workorder::work_orders.progress.label'))
                            ->helperText(__('workorder::work_orders.progress.hint'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(5)
                            ->default(0)
                            ->suffix('%'),

                        TextEntry::make('effort_summary')
                            ->state(fn (?WorkOrder $record): string => self::effortSummary($record)),
                    ]),
                ])
                ->columns(2),
        ];
    }

    /**
     * @return array<string, Component|Entry>
     */
    public static function getInfolistSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextEntry::make('code'),
                        TextEntry::make('status.name'),
                        TextEntry::make('customer.legal_name'),
                        TextEntry::make('site.name'),
                        TextEntry::make('primaryContact.full_name'),
                        TextEntry::make('title'),
                        TextEntry::make('scheduled_at')->dateTime(),
                        TextEntry::make('delivery_due_at')->label(__('workorder::work_orders.fields.delivery_due_at'))->dateTime(),
                        TextEntry::make('progress_percent')->label(__('workorder::work_orders.progress.label')),
                        TextEntry::make('planned_minutes'),
                    ]),
                    TextEntry::make('description')->columnSpanFull(),
                    TextEntry::make('notes')->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }

    /**
     * Rosso quando la data promessa è alle spalle e la commessa non è chiusa,
     * ambra nell'ultima settimana prima, neutro altrimenti.
     */
    public static function deliveryColour(?string $dueAt, WorkOrder $workOrder): string
    {
        if ($dueAt === null || $dueAt === '' || $workOrder->completed_at !== null || $workOrder->closed_at !== null) {
            return 'gray';
        }

        $due = CarbonImmutable::parse($dueAt);
        $now = CarbonImmutable::now();

        return match (true) {
            $due->isBefore($now) => 'danger',
            $due->isBefore($now->addWeek()) => 'warning',
            default => 'gray',
        };
    }

    /**
     * Scostamento previsto/consuntivo in forma leggibile.
     *
     * Senza un previsto non c'e' scostamento da mostrare: dirlo esplicitamente
     * e' piu' onesto che stampare uno zero che sembrerebbe "in perfetto orario".
     */
    public static function effortSummary(?WorkOrder $workOrder): string
    {
        if (! $workOrder instanceof WorkOrder || ! $workOrder->exists) {
            return __('workorder::work_orders.effort.not_planned');
        }

        $effort = app(ComputeWorkOrderEffortAction::class)->execute($workOrder);
        $variance = $effort->varianceMinutes();

        if ($variance === null) {
            return __('workorder::work_orders.effort.not_planned');
        }

        $percent = $effort->variancePercent();

        return __('workorder::work_orders.effort.summary', [
            'planned' => self::formatMinutes($effort->plannedMinutes ?? 0),
            'actual' => self::formatMinutes($effort->actualMinutes),
            'variance' => sprintf(
                '%s%s%s',
                $variance > 0 ? '+' : '',
                self::formatMinutes($variance),
                $percent !== null ? sprintf(' (%s%s%%)', $percent > 0 ? '+' : '', number_format($percent, 1, ',', '.')) : '',
            ),
        ]);
    }

    /**
     * Pre-fatturazione: l'ultimo momento in cui l'extra preventivo si puo'
     * ancora incassare invece di regalarlo.
     */
    public static function preInvoiceAction(): Action
    {
        return Action::make('preInvoice')
            ->label(__('billing::billing.pre_invoice.action'))
            ->icon('heroicon-o-receipt-percent')
            ->color('gray')
            ->modalHeading(__('billing::billing.pre_invoice.title'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('billing::billing.pre_invoice.submit'))
            ->modalContent(fn (WorkOrder $record): View => view('billing::filament.pre-invoice', [
                'draft' => app(BuildWorkOrderPreInvoiceAction::class)->execute($record),
            ]))
            ->visible(fn (): bool => ! request()->is('customer/*'));
    }

    public static function effortBadge(WorkOrder $workOrder): string
    {
        $effort = app(ComputeWorkOrderEffortAction::class)->execute($workOrder);
        $variance = $effort->varianceMinutes();

        if ($variance === null) {
            return __('workorder::work_orders.effort.not_planned');
        }

        return sprintf(
            '%s %s%s',
            $effort->isOverBudget()
                ? __('workorder::work_orders.effort.over_budget')
                : __('workorder::work_orders.effort.within_budget'),
            $variance > 0 ? '+' : '',
            self::formatMinutes($variance),
        );
    }

    public static function effortColor(WorkOrder $workOrder): string
    {
        $effort = app(ComputeWorkOrderEffortAction::class)->execute($workOrder);

        if ($effort->varianceMinutes() === null) {
            return 'gray';
        }

        return $effort->isOverBudget() ? 'danger' : 'success';
    }

    private static function formatMinutes(int $minutes): string
    {
        $absolute = abs($minutes);
        $sign = $minutes < 0 ? '-' : '';

        return sprintf('%s%dh %02dm', $sign, intdiv($absolute, 60), $absolute % 60);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                // Lo sforamento va visto in lista, non solo aprendo la commessa.
                Tables\Columns\TextColumn::make('effort_variance')
                    ->state(fn (WorkOrder $record): string => self::effortBadge($record))
                    ->badge()
                    ->color(fn (WorkOrder $record): string => self::effortColor($record))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.legal_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('site.name')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(60)
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('notes')
                    ->limit(60)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('progress_percent')
                    ->label(__('workorder::work_orders.progress.label'))
                    ->badge()
                    ->color(fn (?int $state): string => match (true) {
                        $state === null || $state === 0 => 'gray',
                        $state >= 100 => 'success',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (?int $state): string => ((int) $state).'%')
                    ->sortable(),
                // I ritardi di consegna devono urlare dalla lista.
                Tables\Columns\TextColumn::make('delivery_due_at')
                    ->label(__('workorder::work_orders.fields.delivery_due_at'))
                    ->dateTime()
                    ->sortable()
                    ->color(fn (?string $state, WorkOrder $record): string => self::deliveryColour($state, $record))
                    ->weight(fn (?string $state, WorkOrder $record): ?string => self::deliveryColour($state, $record) === 'danger' ? 'bold' : null),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('customer_id')
                    ->relationship('customer', 'legal_name', fn (Builder $query): Builder => $query->whereNotNull('legal_name')),
                SelectFilter::make('customer_site_id')
                    ->relationship('site', 'name', fn (Builder $query): Builder => $query->whereNotNull('name')),
                SelectFilter::make('status_id')
                    ->relationship('status', 'name', fn (Builder $query): Builder => $query->whereNotNull('name')),
                Filter::make('scheduled_between')
                    ->label(__('workorder::work_orders.fields.report_from').' / '.__('workorder::work_orders.fields.report_to'))
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $from = is_string($data['from'] ?? null) ? $data['from'] : null;
                        $to = is_string($data['to'] ?? null) ? $data['to'] : null;

                        return $query
                            ->when(
                                filled($from),
                                fn (Builder $innerQuery): Builder => $innerQuery->whereDate('scheduled_at', '>=', $from),
                            )
                            ->when(
                                filled($to),
                                fn (Builder $innerQuery): Builder => $innerQuery->whereDate('scheduled_at', '<=', $to),
                            );
                    }),
            ])
            ->recordActions([
                Action::make('exportReport')
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('success')
                    ->visible(function (): bool {
                        /** @var User|null $user */
                        $user = Auth::user();

                        if (! request()->is('admin/*')) {
                            return ! ($user instanceof User && ! AccountFeatures::hasFeature($user, 'operator.work_orders', true));
                        }

                        return AccountFeatures::hasFeature($user, 'admin.reports', true);
                    })
                    ->schema([
                        Radio::make('format')
                            ->options([
                                'pdf' => 'PDF',
                                'docx' => 'Word (DOCX)',
                            ])
                            ->default('pdf')
                            ->required()
                            ->inline(),
                        DatePicker::make('from')
                            ->displayFormat('d/m/Y')
                            ->default(now()->startOfMonth()->toDateString())
                            ->required(),
                        DatePicker::make('to')
                            ->displayFormat('d/m/Y')
                            ->default(now()->endOfMonth()->toDateString())
                            ->required(),
                    ])
                    ->action(function (WorkOrder $record, array $data): mixed {
                        $format = match (is_string($data['format'] ?? null) ? $data['format'] : 'pdf') {
                            'docx' => 'docx',
                            default => 'pdf',
                        };
                        $fromRaw = is_string($data['from'] ?? null) ? $data['from'] : now()->startOfMonth()->toDateString();
                        $toRaw = is_string($data['to'] ?? null) ? $data['to'] : now()->endOfMonth()->toDateString();
                        $from = CarbonImmutable::parse($fromRaw)->startOfDay();
                        $to = CarbonImmutable::parse($toRaw)->endOfDay();

                        $result = app(GenerateWorkOrderReportAction::class)->execute(
                            workOrder: $record,
                            from: $from,
                            to: $to,
                            format: $format,
                        );

                        return response()->streamDownload(
                            function () use ($result): void {
                                echo $result['content'];
                            },
                            $result['filename'],
                            ['Content-Type' => $result['mime']],
                        );
                    }),
                self::preInvoiceAction(),
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
                RestoreBulkAction::make()
                    ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
                ForceDeleteBulkAction::make()
                    ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (request()->is('operator/*') && ! (bool) config('app.demo_mode')) {
            $userId = Auth::id();
            if ($userId !== null) {
                /** @var User|null $user */
                $user = Auth::user();

                if (! $user instanceof User) {
                    $query->whereRaw('1=0');
                } elseif (! AccountFeatures::hasFeature($user, 'operator.work_orders', true)) {
                    $query->whereRaw('1=0');
                } else {
                    AccountFeatures::scopeAssignedWorkOrders($query, $user);
                }
            } else {
                $query->whereRaw('1=0');
            }
        }

        /** @var User|null $user */
        $user = Auth::user();
        if (request()->is('admin/*') && $user instanceof User && AccountFeatures::hasAssignedPerimeter($user)) {
            AccountFeatures::scopeAssignedWorkOrders($query, $user);
        }

        if (request()->is('customer/*')) {
            /** @var User|null $user */
            $user = Auth::user();
            if (is_object($user)) {
                $id = $user->customer_id ?? 0;
                $customerId = is_numeric($id) ? (int) $id : 0;
            } else {
                $customerId = 0;
            }
            if ($customerId > 0) {
                $query->where('customer_id', $customerId);
            } else {
                $query->whereRaw('1=0');
            }
        }

        return $query;
    }

    public static function getRelations(): array
    {
        if (request()->is('customer/*')) {
            return [
                MaterialsRelationManager::class, ];
        }

        $relations = [
            ActivitiesRelationManager::class,
            AssignmentsRelationManager::class,
            InterventionsRelationManager::class,
            PublicProcurementContractsRelationManager::class,
            StatusEventsRelationManager::class,
        ];

        /** @var User|null $user */
        $user = Auth::user();
        if ($user instanceof User && self::artisanReadinessIsAvailable($user)) {
            $relations[] = ComplianceDocumentsRelationManager::class;
            $relations[] = ChangeOrdersRelationManager::class;
            $relations[] = ReadinessItemsRelationManager::class;
        }

        return $relations;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkOrders::route('/'),
            'create' => Pages\CreateWorkOrder::route('/create'),
            'view' => Pages\ViewWorkOrder::route('/{record}'),
            'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.work_orders',
            operatorFeature: 'operator.work_orders',
            adminDefault: true,
            operatorDefault: true,
            fallback: ! request()->is('customer/*'),
        );
    }

    private static function artisanReadinessIsAvailable(User $user): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            $user,
            adminFeature: 'admin.artisan_readiness',
            operatorFeature: 'operator.artisan_readiness',
            adminDefault: false,
            operatorDefault: false,
            fallback: false,
        );
    }

    /**
     * @return array<int, string>
     */
    private static function getCustomerOptionsForCurrentPanel(): array
    {
        $query = Customer::query()->whereNotNull('legal_name')->orderBy('legal_name');

        /** @var User|null $user */
        $user = Auth::user();

        if (request()->is('admin/*') && $user instanceof User && AccountFeatures::hasAssignedPerimeter($user)) {
            /** @var Builder<Model> $scopedQuery */
            $scopedQuery = $query;
            AccountFeatures::scopeAssignedCustomers($scopedQuery, $user);
        }

        /** @var array<int, string> */
        return $query->pluck('legal_name', 'id')
            ->map(static fn (mixed $name): string => is_string($name) ? $name : '')
            ->all();
    }
}
