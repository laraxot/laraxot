<x-filament-panels::page>
    <div class="mx-auto flex w-full max-w-xl flex-col gap-5">
        {{-- Connection status --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
            @if ($connected)
                <div class="flex items-center gap-2 text-sm font-semibold text-success-600 dark:text-success-400">
                    <x-heroicon-o-check-circle class="h-5 w-5" />
                    {{ __('interventions.google_calendar.connected', ['email' => $googleEmail ?: '—']) }}
                </div>
                @if ($lastSyncedAt)
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('interventions.google_calendar.last_synced', ['at' => $lastSyncedAt]) }}</p>
                @endif
                @if ($hasError)
                    <p class="mt-1 text-xs font-medium text-danger-600 dark:text-danger-400">{{ __('interventions.google_calendar.error') }}</p>
                @endif
                <a href="{{ $this->disconnectUrl() }}" class="mt-3 inline-flex items-center gap-2 rounded-lg bg-danger-50 px-3 py-2 text-sm font-medium text-danger-700 hover:bg-danger-100 dark:bg-danger-500/10 dark:text-danger-300">
                    <x-heroicon-o-x-mark class="h-4 w-4" />
                    {{ __('interventions.google_calendar.disconnect') }}
                </a>
            @else
                <div class="flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                    <x-heroicon-o-exclamation-circle class="h-5 w-5" />
                    {{ __('interventions.google_calendar.not_connected') }}
                </div>
                <a href="{{ $this->connectUrl() }}" class="mt-3 inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-primary-500">
                    <x-heroicon-o-calendar-days class="h-4 w-4" />
                    {{ __('interventions.google_calendar.connect') }}
                </a>
            @endif
        </div>

        {{-- Settings --}}
        <div class="flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <label class="flex items-center justify-between gap-3 text-sm">
                <span class="font-medium text-gray-700 dark:text-gray-200">{{ __('interventions.google_calendar.enabled') }}</span>
                <input type="checkbox" wire:model="enabled" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
            </label>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('interventions.google_calendar.calendar_id') }}</label>
                <input type="text" wire:model="calendarId" placeholder="primary" class="w-full rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                <p class="mt-1 text-[11px] text-gray-400">{{ __('interventions.google_calendar.calendar_id_hint') }}</p>
            </div>
            <button type="button" wire:click="save" class="inline-flex w-fit items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-primary-500">
                {{ __('interventions.google_calendar.save') }}
            </button>
        </div>

        <p class="text-[11px] text-gray-400">{{ __('interventions.google_calendar.help') }}</p>
    </div>
</x-filament-panels::page>
