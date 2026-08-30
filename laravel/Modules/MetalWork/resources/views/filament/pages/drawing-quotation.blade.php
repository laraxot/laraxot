<x-filament-panels::page>
    @php
        $money = static fn (?float $value): string => $value === null ? '—' : number_format($value, 2, ',', '.') . ' €';
        $num = static fn (?float $value, int $decimals = 2): string => $value === null ? '—' : number_format($value, $decimals, ',', '.');
        $cardClass = 'rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900';
        $thClass = 'px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400';
        $tdClass = 'px-3 py-2 text-sm text-slate-700 dark:text-slate-200';
        $inputClass = 'h-10 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100';
        $labelClass = 'grid gap-1 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400';
    @endphp

    <div class="space-y-6">
        {{-- Step 1 — upload --}}
        <div class="{{ $cardClass }}">
            <div class="space-y-1">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">
                    {{ __('metalwork::metalwork.quotation.steps.upload') }}
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-300">
                    {{ __('metalwork::metalwork.quotation.upload_hint') }}
                </p>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('metalwork::metalwork.quotation.fields.drawing') }}
                    <input type="file" wire:model="drawingFile" accept=".stp,.step"
                        class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-800 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-700 dark:text-slate-300" />
                    @error('drawingFile')
                        <span class="text-xs font-semibold text-danger-600">{{ $message }}</span>
                    @enderror
                </label>
                <button type="button" wire:click="parseDrawing" wire:loading.attr="disabled" wire:target="parseDrawing,drawingFile"
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-slate-800 px-5 text-sm font-semibold text-white transition hover:bg-slate-700 disabled:opacity-50">
                    <span wire:loading.remove wire:target="parseDrawing,drawingFile">{{ __('metalwork::metalwork.quotation.actions.parse') }}</span>
                    <span wire:loading wire:target="parseDrawing,drawingFile">{{ __('metalwork::metalwork.quotation.actions.parsing') }}</span>
                </button>
            </div>

            @if ($recentDrawings->isNotEmpty())
                <div class="mt-6">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        {{ __('metalwork::metalwork.quotation.recent') }}
                    </div>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($recentDrawings as $recent)
                            <button type="button" wire:click="openDrawing({{ $recent->id }})"
                                @class([
                                    'rounded-xl border px-3 py-2 text-left text-xs font-semibold transition',
                                    'border-slate-800 bg-slate-100 text-slate-900 dark:bg-slate-800 dark:text-slate-100' => $drawing !== null && $drawing->id === $recent->id,
                                    'border-slate-300 text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800' => $drawing === null || $drawing->id !== $recent->id,
                                ])>
                                {{ $recent->part_code }}
                                <span class="block font-normal opacity-70">{{ \Illuminate\Support\Str::limit($recent->name, 34) }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if ($drawing === null)
            <div class="{{ $cardClass }} text-sm text-slate-500 dark:text-slate-400">
                {{ __('metalwork::metalwork.quotation.empty') }}
            </div>
        @else
            {{-- Step 2 — what was read from the file --}}
            <div class="{{ $cardClass }}">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">
                            {{ __('metalwork::metalwork.quotation.steps.check') }}
                        </h2>
                        <p class="mt-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
                            {{ $drawing->part_code }} — {{ $drawing->name }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $drawing->source_file }}@if ($drawing->cad_system) · {{ $drawing->cad_system }} @endif
                        </p>
                    </div>
                    <button type="button" wire:click="clearDrawing"
                        class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        {{ __('metalwork::metalwork.quotation.actions.clear') }}
                    </button>
                </div>

                <dl class="mt-4 grid gap-3 sm:grid-cols-3 xl:grid-cols-6">
                    @foreach ([
                        'fields.bbox' => $drawing->bbox_length_mm === null ? '—' : $num((float) $drawing->bbox_length_mm, 1) . ' × ' . $num((float) $drawing->bbox_width_mm, 1) . ' × ' . $num((float) $drawing->bbox_thickness_mm, 1),
                        'fields.solid_count' => $drawing->solid_count,
                        'fields.face_count' => $drawing->face_count,
                        'fields.plane_count' => $drawing->plane_count,
                        'fields.cylinder_count' => $drawing->cylinder_count,
                        'fields.is_assembly' => $drawing->is_assembly ? __('metalwork::metalwork.fields.is_assembly') : '—',
                    ] as $key => $value)
                        <div class="rounded-xl bg-slate-50 px-3 py-2 dark:bg-slate-800/60">
                            <dt class="text-[0.65rem] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                {{ __('metalwork::metalwork.' . $key) }}
                            </dt>
                            <dd class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Step 3 — the bill of materials recovered from the solids --}}
            @if ($components->isNotEmpty())
                <div class="{{ $cardClass }}">
                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">
                            {{ __('metalwork::metalwork.quotation.steps.bill_of_materials') }}
                        </h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            {{ __('metalwork::metalwork.quotation.bill_of_materials_hint', [
                                'solids' => $drawing->solid_count,
                                'rows' => $components->count(),
                            ]) }}
                        </p>
                    </div>

                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                            <thead>
                                <tr>
                                    <th class="{{ $thClass }} text-right">{{ __('metalwork::metalwork.fields.quantity') }}</th>
                                    <th class="{{ $thClass }}">{{ __('metalwork::metalwork.component_fields.dimensions') }}</th>
                                    <th class="{{ $thClass }}">{{ __('metalwork::metalwork.component_fields.shape_code') }}</th>
                                    <th class="{{ $thClass }} text-right">{{ __('metalwork::metalwork.component_fields.hole_count') }}</th>
                                    <th class="{{ $thClass }}">{{ __('metalwork::metalwork.component_fields.raw_material') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                @foreach ($components as $component)
                                    <tr>
                                        <td class="{{ $tdClass }} text-right font-semibold">{{ $component->quantity }} ×</td>
                                        <td class="{{ $tdClass }} whitespace-nowrap">{{ $component->label() }}</td>
                                        <td class="{{ $tdClass }}">
                                            {{ __('metalwork::metalwork.shapes.' . $component->shape_code) }}
                                            <span class="ml-1 text-xs text-slate-500 dark:text-slate-400">
                                                {{ __('metalwork::metalwork.cuts.' . ($component->has_profiled_cut ? 'profiled' : 'square')) }}
                                            </span>
                                        </td>
                                        <td class="{{ $tdClass }} text-right">{{ $component->hole_count ?: '—' }}</td>
                                        <td class="{{ $tdClass }}">
                                            <select wire:change="setComponentMaterial({{ $component->id }}, $event.target.value)"
                                                @class([$inputClass, 'ring-1 ring-primary-500' => $component->is_material_confirmed])>
                                                <option value="">{{ __('metalwork::metalwork.quotation.no_material') }}</option>
                                                @foreach ($materials as $material)
                                                    <option value="{{ $material->id }}" @selected($component->raw_material_id === $material->id)>
                                                        {{ $material->code }} — {{ $material->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Step 4 — inputs, cost lines and totals --}}
            <div class="{{ $cardClass }}">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">
                    {{ __('metalwork::metalwork.quotation.steps.estimate') }}
                </h2>

                <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <label class="{{ $labelClass }} sm:col-span-2">
                        {{ __('metalwork::metalwork.quotation.fields.material') }}
                        <select wire:model.live="rawMaterialId" class="{{ $inputClass }}">
                            <option value="">—</option>
                            @foreach ($materials as $material)
                                <option value="{{ $material->id }}">
                                    {{ $material->code }} · {{ \Illuminate\Support\Str::limit($material->description, 50) }} ({{ $material->unit_code }})
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <label class="{{ $labelClass }}">
                        {{ __('metalwork::metalwork.quotation.fields.quantity') }}
                        <input type="text" wire:model.live.debounce.400ms="quantity" class="{{ $inputClass }}" />
                    </label>
                    <label class="{{ $labelClass }}">
                        {{ __('metalwork::metalwork.quotation.fields.thickness_mm') }}
                        <input type="text" wire:model.live.debounce.400ms="thicknessMm" class="{{ $inputClass }}" placeholder="{{ $drawing->bbox_thickness_mm }}" />
                    </label>
                    <label class="{{ $labelClass }}">
                        {{ __('metalwork::metalwork.quotation.fields.occupancy_percent') }}
                        <input type="text" wire:model.live.debounce.400ms="occupancyPercent" class="{{ $inputClass }}" />
                    </label>
                    <label class="{{ $labelClass }}">
                        {{ __('metalwork::metalwork.quotation.fields.scrap_percent') }}
                        <input type="text" wire:model.live.debounce.400ms="scrapPercent" class="{{ $inputClass }}" />
                    </label>
                    <label class="{{ $labelClass }}">
                        {{ __('metalwork::metalwork.quotation.fields.markup_percent') }}
                        <input type="text" wire:model.live.debounce.400ms="markupPercent" class="{{ $inputClass }}" />
                    </label>
                    <label class="{{ $labelClass }}">
                        {{ __('metalwork::metalwork.quotation.fields.manual_sale_price') }}
                        <input type="text" wire:model.live.debounce.400ms="manualSalePrice" class="{{ $inputClass }}" />
                    </label>
                </div>

                @if ($costing !== null && $costing->hasWarnings())
                    <ul class="mt-4 space-y-2">
                        @foreach ($costing->warnings as $warning)
                            <li class="rounded-xl border border-amber-300 bg-amber-50 px-3 py-2 text-xs font-medium text-amber-900 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                                {{ __('metalwork::metalwork.warnings.' . $warning) }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if ($costing !== null && count($costing->lines) > 0)
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                            <thead>
                                <tr>
                                    <th class="{{ $thClass }}">{{ __('metalwork::metalwork.fields.work_center_code') }}</th>
                                    <th class="{{ $thClass }}">{{ __('metalwork::metalwork.fields.description') }}</th>
                                    <th class="{{ $thClass }} text-right">{{ __('metalwork::metalwork.fields.quantity') }}</th>
                                    <th class="{{ $thClass }}">{{ __('metalwork::metalwork.fields.unit_code') }}</th>
                                    <th class="{{ $thClass }} text-right">{{ __('metalwork::metalwork.fields.unit_cost') }}</th>
                                    <th class="{{ $thClass }} text-right">{{ __('metalwork::metalwork.fields.line_cost') }}</th>
                                    <th class="{{ $thClass }} text-right">{{ __('metalwork::metalwork.fields.markup_percent') }}</th>
                                    <th class="{{ $thClass }} text-right">{{ __('metalwork::metalwork.fields.line_price') }}</th>
                                    <th class="{{ $thClass }}">{{ __('metalwork::metalwork.fields.driver_code') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                @foreach ($costing->lines as $line)
                                    <tr>
                                        <td class="{{ $tdClass }} font-semibold">{{ __('metalwork::metalwork.work_centers_list.' . $line->workCenterCode) }}</td>
                                        <td class="{{ $tdClass }}">{{ $line->description }}</td>
                                        <td class="{{ $tdClass }} text-right">{{ $num($line->quantity, 4) }}</td>
                                        <td class="{{ $tdClass }}">{{ $line->unitCode }}</td>
                                        <td class="{{ $tdClass }} text-right">{{ $num($line->unitCost, 4) }}</td>
                                        <td class="{{ $tdClass }} text-right">{{ $money($line->lineCost) }}</td>
                                        <td class="{{ $tdClass }} text-right">{{ $num($line->markupPercent) }}%</td>
                                        <td class="{{ $tdClass }} text-right font-semibold">{{ $money($line->linePrice) }}</td>
                                        <td class="{{ $tdClass }} text-xs opacity-70">
                                            {{ $line->driverCode ? __('metalwork::metalwork.drivers.' . $line->driverCode) : '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if ($costing !== null)
                    <dl class="mt-6 grid gap-3 sm:grid-cols-3 xl:grid-cols-6">
                        @foreach ([
                            'net_weight' => $num($costing->netWeightKg, 2) . ' kg',
                            'gross_weight' => $num($costing->grossWeightKg, 2) . ' kg',
                            'unit_cost' => $money($costing->unitCost),
                            'unit_price' => $money($costing->unitSalePrice),
                            'price_per_kg' => $costing->pricePerKg() === null ? '—' : $money($costing->pricePerKg()),
                            'total_price' => $money($costing->totalSalePrice()),
                        ] as $key => $value)
                            <div class="rounded-xl bg-slate-50 px-3 py-2 dark:bg-slate-800/60">
                                <dt class="text-[0.65rem] font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    {{ __('metalwork::metalwork.quotation.summary.' . $key) }}
                                </dt>
                                <dd class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                        {{ __('metalwork::metalwork.quotation.summary.margin') }}: {{ $money($costing->margin()) }}
                        @if ($costing->marginPercent() !== null)
                            · {{ __('metalwork::metalwork.quotation.summary.margin_percent') }}: {{ $num($costing->marginPercent()) }}%
                        @endif
                    </p>
                @endif

                <div class="mt-6 grid gap-3 md:grid-cols-[1fr_auto_auto] md:items-end">
                    <label class="{{ $labelClass }}">
                        {{ __('metalwork::metalwork.quotation.fields.customer') }}
                        <select wire:model="customerId" class="{{ $inputClass }}">
                            <option value="">—</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->legal_name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="button" wire:click="saveEstimate"
                        class="inline-flex h-11 items-center justify-center rounded-xl border border-slate-300 px-5 text-sm font-semibold text-slate-800 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-100 dark:hover:bg-slate-800">
                        {{ __('metalwork::metalwork.quotation.actions.save') }}
                    </button>
                    <button type="button" wire:click="createQuotation"
                        class="inline-flex h-11 items-center justify-center rounded-xl bg-slate-800 px-5 text-sm font-semibold text-white transition hover:bg-slate-700">
                        {{ __('metalwork::metalwork.quotation.actions.create_quotation') }}
                    </button>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
