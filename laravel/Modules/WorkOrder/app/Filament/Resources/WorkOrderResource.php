<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Billing\Actions\BuildWorkOrderPreInvoiceAction;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;
use Modules\WorkOrder\Actions\ComputeWorkOrderEffortAction;
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
use Modules\Xot\Filament\Resources\XotBaseResource;

final class WorkOrderResource extends XotBaseResource
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

    /**
     * @return array<string, Component|Entry>
     */
    public function getInfolistSchema(): array
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
                        TextEntry::make('delivery_due_at')->dateTime(),
                        TextEntry::make('progress_percent'),
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
     * Esposto public: consumato da WorkOrderResource\Schemas\WorkOrderForm,
     * classe sorella (non sottoclasse) dopo l'estrazione Schemas/Tables.
     *
     * @return array<int, string>
     */
    public static function getCustomerOptionsForCurrentPanel(): array
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
