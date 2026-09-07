<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor\TextColor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Actions\SyncInterventionNotePhotosAction;
use Modules\Intervention\Filament\Resources\InterventionResource\Pages;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\InventoryMovementsRelationManager;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\IssueReportsRelationManager;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\MediaRelationManager;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\SignaturesRelationManager;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\SketchesRelationManager;
use Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers\TimePunchesRelationManager;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\Intervention\Support\DailyNoteHtml;
use Modules\Platform\Settings\SettingsRepository;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class InterventionResource extends XotBaseResource
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
