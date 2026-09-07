<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\Schemas;

use App\Support\AccountFeatures;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\TextColor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use Illuminate\Support\Str;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\Intervention\Support\DailyNoteHtml;
use Modules\Platform\Settings\SettingsRepository;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

final class InterventionForm extends XotBaseResourceForm
{
    protected static string $resource = InterventionResource::class;

    public function getFormColumns(): int
    {
        return 2;
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

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        Select::make('work_order_id')

                            ->options(fn (): array => self::getWorkOrderOptions())
                            ->searchable()
                            ->preload()
                            ->visible(fn (): bool => ! self::isDirectCustomerMode())
                            ->required(fn (): bool => ! self::isDirectCustomerMode()),

                        Select::make('customer_id')

                            ->options(fn (): array => self::getCustomerOptions())
                            ->searchable()
                            ->preload()
                            ->visible(fn (): bool => self::isDirectCustomerMode())
                            ->required(fn (): bool => self::isDirectCustomerMode())
                            ->helperText('Seleziona il cliente per cui stai eseguendo l\'intervento.'),

                        Select::make('assigned_user_ids')

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

                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false)
                            ->visible(fn (): bool => ! self::isDirectCustomerMode()),
                        TextInput::make('amount')

                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('€')
                            ->live()
                            ->helperText(self::isDirectCustomerMode() ? 'Inserisci l\'importo solo se vuoi far vedere subito il consuntivo economico.' : null),
                    ]),

                    Grid::make(3)->components([
                        TextInput::make('work_hours')

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

                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('€')
                            ->default(0)
                            ->live()
                            ->helperText('Lascia 0 se non applichi il diritto di chiamata.'),
                        TextEntry::make('total_amount_preview')

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

                            ->visible(fn (): bool => ! self::isDirectCustomerMode()),
                        DateTimePicker::make('ended_at')

                            ->visible(fn (): bool => ! self::isDirectCustomerMode()),
                    ]),

                    Grid::make(2)->components([
                        TextInput::make('travel_km')

                            ->numeric()
                            ->minValue(0)
                            ->visible(fn (): bool => ! self::isDirectCustomerMode()),
                        TextEntry::make('materials_total_preview')

                            ->state(function (callable $get, ?Intervention $record): string {
                                return number_format(self::previewMaterialsTotal($get('manual_material_lines'), $record), 2, ',', '.').' €';
                            })
                            ->live(),
                    ]),

                    RichEditor::make('notes_html')

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

                        ->columnSpanFull()
                        ->schema([
                            TextInput::make('description'),
                            Grid::make(2)->components([
                                TextInput::make('quantity')

                                    ->numeric()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->default(1)
                                    ->live(),
                                Select::make('unit')

                                    ->options([
                                        'pz' => 'Pz',
                                        'mt' => 'Mt',
                                    ])
                                    ->default('pz'),
                            ]),
                            TextInput::make('unit_price')

                                ->numeric()
                                ->minValue(0)
                                ->step(0.01)
                                ->prefix('€')
                                ->default(0)
                                ->live(),
                            TextEntry::make('line_total_preview')

                                ->state(function (callable $get): string {
                                    $qtyValue = $get('quantity') ?? 0;
                                    $quantity = is_numeric($qtyValue) ? (float) $qtyValue : 0.0;
                                    $priceValue = $get('unit_price') ?? 0;
                                    $unitPrice = is_numeric($priceValue) ? (float) $priceValue : 0.0;

                                    return number_format($quantity * $unitPrice, 2, ',', '.').' €';
                                })
                                ->live(),

                            TextEntry::make('extra_preview')

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

                                ->options(self::extraStatusOptions())
                                ->default('pending')
                                ->selectablePlaceholder(false)
                                ->helperText(__('interventions.extra.hint'))
                                ->visible(fn (callable $get): bool => (is_numeric($get('extra_quantity')) ? (float) $get('extra_quantity') : 0.0) > 0.0),

                            Hidden::make('extra_quantity'),
                            Hidden::make('extra_amount'),
                        ])
                        ->default(fn (?Intervention $record): array => self::materialLinesFormState($record))
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

    public static function notePhotoDirectory(Intervention|string|null $record, mixed $workOrderId): string
    {
        if ($record instanceof Intervention && $record->exists) {
            return "media/interventions/{$record->id}/note-photos";
        }

        $normalizedWorkOrderId = max(0, (int) (is_numeric($workOrderId) ? $workOrderId : 0));

        return "media/interventions/draft/work-order-{$normalizedWorkOrderId}/note-photos";
    }

    public static function isDirectCustomerMode(): bool
    {
        return app(SettingsRepository::class)->getString('interventions.work_mode', 'work_order') === 'customer';
    }

    /**
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

    public static function ensureString(mixed $value): string
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

    public static function previewMaterialsTotal(mixed $lines, ?Intervention $record): float
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

    public static function computeManualMaterialsTotal(mixed $lines): float
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
    public static function getPreviousNoteSuggestions(string $currentValue = ''): array
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

    public static function applyNoteSuggestion(string $currentValue, string $suggestion): string
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

    public static function normalizeTextState(mixed $value): string
    {
        if (is_string($value)) {
            return DailyNoteHtml::toPlainText($value);
        }

        if (is_numeric($value)) {
            return (string) $value;
        }

        return '';
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

        $user = Auth::user();
        if (
            request()->is('admin/*')
            && $user instanceof User
            && AccountFeatures::hasAssignedPerimeter($user)
            && AccountFeatures::hasFeature($user, 'admin.team_interventions', true)
        ) {
            $query->whereExists(function (Builder $subQuery) use ($user): void {
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

        $user = Auth::user();
        if ($user instanceof User && AccountFeatures::hasAssignedPerimeter($user)) {
            $query->whereExists(function (Builder $subQuery) use ($user): void {
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
            ->whereExists(function (Builder $query) use ($modelHasRolesTable, $rolesTable, $userModel): void {
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
}
