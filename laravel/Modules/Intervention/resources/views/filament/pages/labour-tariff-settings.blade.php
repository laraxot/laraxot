<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <h2 class="text-lg font-semibold text-gray-950 dark:text-white">{{ __('interventions.labour_tariff.title') }}</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ __('interventions.labour_tariff.intro') }}</p>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <div class="space-y-6 xl:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">{{ __('interventions.labour_tariff.base') }}</h3>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ __('interventions.labour_tariff.hourly_price') }}</span>
                            <input type="number" step="0.01" min="0" wire:model.live.debounce.500ms="hourlyPrice" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                            <span class="mt-1 block text-xs text-gray-500">{{ __('interventions.labour_tariff.hourly_price_hint') }}</span>
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ __('interventions.labour_tariff.callout_fee') }}</span>
                            <input type="number" step="0.01" min="0" wire:model.live.debounce.500ms="calloutFee" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                            <span class="mt-1 block text-xs text-gray-500">{{ __('interventions.labour_tariff.callout_fee_hint') }}</span>
                        </label>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                    <h3 class="text-base font-semibold text-gray-950 dark:text-white">{{ __('interventions.labour_tariff.surcharges') }}</h3>
                    <p class="mt-1 text-xs text-gray-500">{{ __('interventions.labour_tariff.surcharges_hint') }}</p>
                    <div class="mt-4 grid grid-cols-2 gap-4 md:grid-cols-4">
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ __('interventions.labour_tariff.night_percent') }}</span>
                            <input type="number" min="0" max="300" wire:model.live.debounce.500ms="nightPercent" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ __('interventions.labour_tariff.night_from') }}</span>
                            <input type="number" min="0" max="23" wire:model.live.debounce.500ms="nightFromHour" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ __('interventions.labour_tariff.night_to') }}</span>
                            <input type="number" min="0" max="23" wire:model.live.debounce.500ms="nightToHour" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                        <label class="block text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ __('interventions.labour_tariff.weekend_percent') }}</span>
                            <input type="number" min="0" max="300" wire:model.live.debounce.500ms="weekendPercent" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-white/10 dark:bg-white/5" />
                        </label>
                    </div>
                </div>

                <div class="flex justify-end">
                    <x-filament::button wire:click="save" wire:loading.attr="disabled">
                        {{ __('interventions.labour_tariff.save') }}
                    </x-filament::button>
                </div>
            </div>

            {{-- Same numbers the voice report will produce, so a change is
                 visible here before it reaches a customer's invoice. --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
                <h3 class="text-base font-semibold text-gray-950 dark:text-white">{{ __('interventions.labour_tariff.preview') }}</h3>
                <p class="mt-1 text-xs text-gray-500">{{ __('interventions.labour_tariff.preview_hint') }}</p>

                <div class="mt-4 space-y-2">
                    @foreach ($this->preview() as $row)
                        <div class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 text-sm dark:bg-white/5">
                            <span class="flex items-center gap-2 text-gray-700 dark:text-gray-200">
                                {{ $row['label'] }}
                                @if ($row['surcharge'] > 0)
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">+{{ $row['surcharge'] }}%</span>
                                @endif
                            </span>
                            <span class="font-semibold text-gray-900 dark:text-gray-50">{{ number_format($row['amount'], 2, ',', '.') }} €</span>
                        </div>
                    @endforeach
                </div>

                @if ($hourlyPrice <= 0)
                    <p class="mt-4 rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-800 dark:bg-amber-500/10 dark:text-amber-200">
                        {{ __('interventions.labour_tariff.disabled_warning') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
