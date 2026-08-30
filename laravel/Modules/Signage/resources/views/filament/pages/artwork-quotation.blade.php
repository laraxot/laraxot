<x-filament-panels::page>
    @php
        $money = static fn (?float $value): string => $value === null ? '—' : number_format($value, 2, ',', '.') . ' €';
        $qty = static fn (?float $value): string => $value === null ? '—' : rtrim(rtrim(number_format($value, 3, ',', '.'), '0'), ',');
        $cardClass = 'rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900';
        $thClass = 'px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400';
        $tdClass = 'px-3 py-2 text-sm text-slate-700 dark:text-slate-200';
        $inputClass = 'h-10 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-900 shadow-sm dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100';
    @endphp

    <div class="space-y-6">
        {{-- Step 1 — upload --}}
        <div class="{{ $cardClass }}">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div class="space-y-1">
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">
                        {{ __('signage::signage.artwork_quotation.steps.upload') }}
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        {{ __('signage::signage.artwork_quotation.upload_hint') }}
                    </p>
                </div>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
                <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                    {{ __('signage::signage.artwork_quotation.fields.artwork') }}
                    <input type="file" wire:model="artwork" accept=".ai,.pdf"
                        class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-700 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-600 dark:text-slate-300" />
                    @error('artwork')
                        <span class="text-xs font-semibold text-danger-600">{{ $message }}</span>
                    @enderror
                </label>
                <button type="button" wire:click="extract" wire:loading.attr="disabled" wire:target="extract,artwork"
                    class="inline-flex h-11 items-center justify-center rounded-xl bg-blue-700 px-5 text-sm font-semibold text-white transition hover:bg-blue-600 disabled:opacity-50">
                    <span wire:loading.remove wire:target="extract,artwork">{{ __('signage::signage.artwork_quotation.actions.extract') }}</span>
                    <span wire:loading wire:target="extract,artwork">{{ __('signage::signage.artwork_quotation.actions.extracting') }}</span>
                </button>
            </div>

            @if ($recentSpecs->isNotEmpty())
                <div class="mt-6">
                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        {{ __('signage::signage.artwork_quotation.recent') }}
                    </div>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($recentSpecs as $recent)
                            <button type="button" wire:click="openSpec({{ $recent->id }})"
                                @class([
                                    'rounded-xl border px-3 py-2 text-xs font-semibold transition',
                                    'border-blue-600 bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-200' => $spec !== null && $spec->id === $recent->id,
                                    'border-slate-300 text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800' => $spec === null || $spec->id !== $recent->id,
                                ])>
                                #{{ $recent->id }} · {{ $recent->brand ?? $recent->source_file }}
                                @if ($recent->sign_type_code) <span class="opacity-70">{{ $recent->sign_type_code }}</span> @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if ($spec === null)
            <div class="{{ $cardClass }} text-sm text-slate-500 dark:text-slate-400">
                {{ __('signage::signage.artwork_quotation.empty') }}
            </div>
        @else
            {{-- Step 2 — what was read from the drawing --}}
            <div class="{{ $cardClass }}">
                <div class="flex items-start justify-between gap-4">
                    <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">
                        {{ __('signage::signage.artwork_quotation.steps.check') }}
                    </h2>
                    <button type="button" wire:click="clearSpec"
                        class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        {{ __('signage::signage.artwork_quotation.actions.clear') }}
                    </button>
                </div>

                <div class="mt-4 grid gap-6 xl:grid-cols-[1fr_20rem]">
                <dl class="grid gap-4 sm:grid-cols-2">
                    @php
                        $facts = [
                            __('signage::signage.artwork_quotation.fields.drawing') => $spec->drawing_title ?? $spec->source_file,
                            __('signage::signage.artwork_quotation.fields.customer_name') => $spec->customer_name,
                            __('signage::signage.artwork_quotation.fields.brand') => trim(($spec->brand ?? '') . ' ' . ($spec->shop ?? '')),
                            __('signage::signage.artwork_quotation.fields.sign_type_code') => $spec->sign_type_code,
                            __('signage::signage.artwork_quotation.fields.pieces') => $spec->quantity,
                            __('signage::signage.artwork_quotation.fields.dimensions') => $spec->width_mm && $spec->height_mm ? $spec->width_mm . ' × ' . $spec->height_mm . ' mm' : null,
                            __('signage::signage.artwork_quotation.fields.lighting') => $spec->lighting,
                            __('signage::signage.artwork_quotation.fields.painting') => $spec->painting,
                        ];
                    @endphp
                    @foreach ($facts as $label => $value)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $label }}</dt>
                            <dd class="mt-1 text-sm font-semibold text-slate-950 dark:text-slate-100">
                                {{ $value !== null && $value !== '' ? $value : '—' }}
                            </dd>
                        </div>
                    @endforeach
                </dl>

                {{-- Il disegno di riferimento a fianco: e' con quello che l'operatore
                     controlla se quello che abbiamo letto ha senso. --}}
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        {{ __('signage::signage.artwork_quotation.preview.title') }}
                    </div>
                    @if ($artworkUrl !== null)
                        <div class="mt-2 overflow-hidden rounded-xl border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-950">
                            <object data="{{ $artworkUrl }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                type="application/pdf" class="h-64 w-full">
                                <div class="p-4 text-xs text-slate-500 dark:text-slate-400">
                                    {{ __('signage::signage.artwork_quotation.preview.unsupported') }}
                                </div>
                            </object>
                        </div>
                        <a href="{{ $artworkUrl }}" target="_blank" rel="noopener"
                            class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-blue-700 hover:underline dark:text-blue-300">
                            {{ __('signage::signage.artwork_quotation.preview.open') }} →
                        </a>
                        <div class="mt-1 text-xs text-slate-400 dark:text-slate-500">{{ $spec->source_file }}</div>
                    @else
                        <div class="mt-2 rounded-xl border border-dashed border-slate-300 p-4 text-xs text-slate-500 dark:border-slate-700 dark:text-slate-400">
                            {{ __('signage::signage.artwork_quotation.preview.missing') }}
                        </div>
                    @endif
                </div>
                </div>
            </div>

            {{-- Step 3 — quantities and quotation --}}
            <div class="{{ $cardClass }}">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                    <div class="space-y-1">
                        <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">
                            {{ __('signage::signage.artwork_quotation.steps.price') }}
                        </h2>
                        <p class="text-sm text-slate-600 dark:text-slate-300">
                            {{ __('signage::signage.artwork_quotation.price_hint') }}
                        </p>
                    </div>
                    <label class="grid w-full gap-2 text-sm font-medium text-slate-700 xl:w-48 dark:text-slate-200">
                        {{ __('signage::signage.artwork_quotation.fields.markup') }}
                        <input type="number" step="0.1" min="0" wire:model.live.debounce.500ms="markupPercent" class="{{ $inputClass }}" />
                    </label>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[900px] divide-y divide-slate-200 dark:divide-slate-800">
                        <thead>
                            <tr>
                                <th class="{{ $thClass }}">{{ __('signage::signage.artwork_quotation.columns.component') }}</th>
                                <th class="{{ $thClass }}">{{ __('signage::signage.artwork_quotation.columns.item') }}</th>
                                <th class="{{ $thClass }}">{{ __('signage::signage.artwork_quotation.columns.unit') }}</th>
                                <th class="{{ $thClass }}" style="width: 9rem">{{ __('signage::signage.artwork_quotation.columns.quantity') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('signage::signage.artwork_quotation.columns.unit_cost') }}</th>
                                <th class="{{ $thClass }} text-right">{{ __('signage::signage.artwork_quotation.columns.line_total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($quotation->lines as $line)
                                <tr @class(['bg-warning-50 dark:bg-warning-950/30' => $line->needsInput])>
                                    <td class="{{ $tdClass }}">
                                        <div class="font-medium">{{ $line->label !== '' ? $line->label : $line->role }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $line->role }}</div>
                                    </td>
                                    <td class="{{ $tdClass }}">
                                        {{ $line->priceListItemName ?? '—' }}
                                        @if ($line->needsInput && $line->note)
                                            <div class="text-xs font-semibold text-warning-600">⚠ {{ $line->note }}</div>
                                        @endif
                                    </td>
                                    <td class="{{ $tdClass }}">{{ $line->unit ?? '—' }}</td>
                                    <td class="{{ $tdClass }}">
                                        <input type="text" inputmode="decimal"
                                            wire:model.live.debounce.600ms="quantities.{{ $line->role }}"
                                            placeholder="{{ $line->quantity !== null ? $qty($line->quantity) : __('signage::signage.artwork_quotation.to_fill') }}"
                                            class="{{ $inputClass }}" />
                                    </td>
                                    <td class="{{ $tdClass }} text-right">{{ $money($line->unitCost) }}</td>
                                    <td class="{{ $tdClass }} text-right font-semibold">{{ $money($line->lineTotal) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="{{ $tdClass }}" colspan="6">{{ __('signage::signage.artwork_quotation.no_lines') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 px-5 py-4 dark:border-slate-800">
                        <div class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('signage::signage.artwork_quotation.totals.cost') }}</div>
                        <div class="mt-1 text-xl font-semibold text-slate-950 dark:text-slate-100">{{ $money($quotation->totalCost) }}</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 px-5 py-4 dark:border-slate-800">
                        <div class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('signage::signage.artwork_quotation.totals.markup') }}</div>
                        <div class="mt-1 text-xl font-semibold text-slate-950 dark:text-slate-100">{{ number_format($quotation->markupPercent, 1, ',', '.') }}%</div>
                    </div>
                    <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 dark:border-blue-900 dark:bg-blue-950">
                        <div class="text-xs uppercase tracking-wide text-blue-700 dark:text-blue-200">{{ __('signage::signage.artwork_quotation.totals.sell') }}</div>
                        <div class="mt-1 text-xl font-semibold text-blue-900 dark:text-blue-100">{{ $money($quotation->totalSell) }}</div>
                    </div>
                </div>

                @if ($quotation->hasMissing)
                    <p class="mt-4 rounded-xl bg-warning-50 px-4 py-3 text-sm text-warning-700 dark:bg-warning-950/40 dark:text-warning-200">
                        {{ __('signage::signage.artwork_quotation.missing_warning') }}
                    </p>
                @endif
            </div>

            {{-- Step 4 — create the quotation --}}
            <div class="{{ $cardClass }}">
                <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">
                    {{ __('signage::signage.artwork_quotation.steps.save') }}
                </h2>
                <div class="mt-4 grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
                    <label class="grid gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('signage::signage.artwork_quotation.fields.customer') }}
                        <select wire:model.live="customerId" class="{{ $inputClass }}">
                            <option value="">{{ __('signage::signage.artwork_quotation.fields.customer_placeholder') }}</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->legal_name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="button" wire:click="createQuotation" wire:loading.attr="disabled" wire:target="createQuotation"
                        class="inline-flex h-11 items-center justify-center rounded-xl bg-emerald-700 px-5 text-sm font-semibold text-white transition hover:bg-emerald-600 disabled:opacity-50">
                        {{ __('signage::signage.artwork_quotation.actions.create_quotation') }}
                    </button>
                </div>
                <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                    {{ __('signage::signage.artwork_quotation.save_hint') }}
                </p>
            </div>
        @endif
    </div>
</x-filament-panels::page>
