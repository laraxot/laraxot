<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources;

use App\Models\User;
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
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Billing\Actions\BuildWorkOrderPreInvoiceAction;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\WorkOrder\Actions\ComputeWorkOrderEffortAction;
use Modules\WorkOrder\Actions\GenerateWorkOrderReportAction;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\Pages;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\ActivitiesRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\AssignmentsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\ChangeOrdersRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\ComplianceDocumentsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\InterventionsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\PublicProcurementContractsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\ReadinessItemsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\StatusEventsRelationManager;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;

final class WorkOrderResource extends Resource
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
    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        /** @var \Modules\WorkOrder\Models\WorkOrder $record */
        return array_filter([
            __('work_orders.fields.customer') => $record->customer?->legal_name,
            __('work_orders.fields.title') => $record->title,
        ]);
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

    public static function canEdit($record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function canView($record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('work_orders.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('work_orders.work_order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('work_orders.work_orders');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('code')
                            ->label(__('work_orders.fields.code'))
                            ->maxLength(255),

                        Select::make('status_id')
                            ->label(__('work_orders.fields.status'))
                            ->options(fn () => WorkOrderStatus::query()->whereNotNull('name')->orderBy('sort_order')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload(),
                    ]),

                    Select::make('customer_id')
                        ->label(__('work_orders.fields.customer'))
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
                            ->label(__('work_orders.fields.site'))
                            ->options(function (callable $get): array {
                                $customerId = (int) ($get('customer_id') ?? 0);
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
                            ->label(__('work_orders.fields.primary_contact'))
                            ->options(function (callable $get): array {
                                $customerId = (int) ($get('customer_id') ?? 0);
                                if ($customerId <= 0) {
                                    return [];
                                }

                                $siteId = (int) ($get('customer_site_id') ?? 0);

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
                        ->label(__('work_orders.fields.title'))
                        ->required()
                        ->maxLength(255),

                    Textarea::make('description')
                        ->label(__('work_orders.fields.description'))
                        ->columnSpanFull(),

                    Textarea::make('notes')
                        ->label(__('work_orders.fields.notes'))
                        ->columnSpanFull(),

                    Grid::make(2)->components([
                        DateTimePicker::make('opened_at')->label(__('work_orders.fields.opened_at')),
                        DateTimePicker::make('scheduled_at')->label(__('work_orders.fields.scheduled_at')),
                        DateTimePicker::make('started_at')->label(__('work_orders.fields.started_at')),
                        DateTimePicker::make('completed_at')->label(__('work_orders.fields.completed_at')),
                    ]),

                    Grid::make(2)->components([
                        TextInput::make('planned_minutes')
                            ->label(__('work_orders.effort.planned_minutes'))
                            ->helperText(__('work_orders.effort.hint'))
                            ->numeric()
                            ->minValue(0)
                            ->step(1),

                        Placeholder::make('effort_summary')
                            ->label(__('work_orders.effort.variance'))
                            ->content(fn (?WorkOrder $record): string => self::effortSummary($record)),
                    ]),
                ])
                ->columns(2),
        ]);
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
            return __('work_orders.effort.not_planned');
        }

        $effort = app(ComputeWorkOrderEffortAction::class)->execute($workOrder);
        $variance = $effort->varianceMinutes();

        if ($variance === null) {
            return __('work_orders.effort.not_planned');
        }

        $percent = $effort->variancePercent();

        return __('work_orders.effort.summary', [
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
            ->label(__('billing.pre_invoice.action'))
            ->icon('heroicon-o-receipt-percent')
            ->color('gray')
            ->modalHeading(__('billing.pre_invoice.title'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('billing.pre_invoice.submit'))
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
            return __('work_orders.effort.not_planned');
        }

        return sprintf(
            '%s %s%s',
            $effort->isOverBudget()
                ? __('work_orders.effort.over_budget')
                : __('work_orders.effort.within_budget'),
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
                    ->label(__('work_orders.fields.code'))
                    ->searchable()
                    ->sortable(),
                // Lo sforamento va visto in lista, non solo aprendo la commessa.
                Tables\Columns\TextColumn::make('effort_variance')
                    ->label(__('work_orders.effort.variance'))
                    ->state(fn (WorkOrder $record): string => self::effortBadge($record))
                    ->badge()
                    ->color(fn (WorkOrder $record): string => self::effortColor($record))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('title')
                    ->label(__('work_orders.fields.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.legal_name')
                    ->label(__('work_orders.fields.customer'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('site.name')
                    ->label(__('work_orders.fields.site'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status.name')
                    ->label(__('work_orders.fields.status'))
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('work_orders.fields.description'))
                    ->limit(60)
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label(__('work_orders.fields.notes'))
                    ->limit(60)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label(__('work_orders.fields.scheduled_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('customer_id')
                    ->label(__('work_orders.fields.customer'))
                    ->relationship('customer', 'legal_name', fn (Builder $query): Builder => $query->whereNotNull('legal_name')),
                SelectFilter::make('customer_site_id')
                    ->label(__('work_orders.fields.site'))
                    ->relationship('site', 'name', fn (Builder $query): Builder => $query->whereNotNull('name')),
                SelectFilter::make('status_id')
                    ->label(__('work_orders.fields.status'))
                    ->relationship('status', 'name', fn (Builder $query): Builder => $query->whereNotNull('name')),
                Filter::make('scheduled_between')
                    ->label(__('work_orders.fields.report_from').' / '.__('work_orders.fields.report_to'))
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label(__('work_orders.fields.report_from')),
                        \Filament\Forms\Components\DatePicker::make('to')
                            ->label(__('work_orders.fields.report_to')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $from = $data['from'] ?? null;
                        $to = $data['to'] ?? null;

                        return $query
                            ->when(
                                filled($from),
                                fn (Builder $innerQuery): Builder => $innerQuery->whereDate('scheduled_at', '>=', (string) $from),
                            )
                            ->when(
                                filled($to),
                                fn (Builder $innerQuery): Builder => $innerQuery->whereDate('scheduled_at', '<=', (string) $to),
                            );
                    }),
            ])
            ->actions([
                Action::make('exportReport')
                    ->label(__('work_orders.actions.export_report'))
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
                    ->form([
                        Radio::make('format')
                            ->label(__('work_orders.fields.report_format'))
                            ->options([
                                'pdf' => 'PDF',
                                'docx' => 'Word (DOCX)',
                            ])
                            ->default('pdf')
                            ->required()
                            ->inline(),
                        DatePicker::make('from')
                            ->label(__('work_orders.fields.report_from'))
                            ->displayFormat('d/m/Y')
                            ->default(now()->startOfMonth()->toDateString())
                            ->required(),
                        DatePicker::make('to')
                            ->label(__('work_orders.fields.report_to'))
                            ->displayFormat('d/m/Y')
                            ->default(now()->endOfMonth()->toDateString())
                            ->required(),
                    ])
                    ->action(function (WorkOrder $record, array $data): mixed {
                        $format = (string) ($data['format'] ?? 'pdf');
                        $from = CarbonImmutable::parse((string) $data['from'])->startOfDay();
                        $to = CarbonImmutable::parse((string) $data['to'])->endOfDay();

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
            ->bulkActions([
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
            /** @var \App\Models\User|null $user */
            $user = Auth::user();
            $customerId = is_object($user) ? (int) ($user->customer_id ?? 0) : 0;
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
            return [];
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
            AccountFeatures::scopeAssignedCustomers($query, $user);
        }

        return $query->pluck('legal_name', 'id')->all();
    }
}
