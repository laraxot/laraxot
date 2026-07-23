<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl bg-white p-4 shadow-sm dark:bg-gray-900">
            <div class="mb-3 flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    wire:click="goToPreviousDay"
                    class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                >
                    {{ __('interventions.actions.yesterday') }}
                </button>
                <button
                    type="button"
                    wire:click="goToToday"
                    class="inline-flex items-center rounded-lg border border-blue-300 bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700 transition hover:bg-blue-100 dark:border-blue-700 dark:bg-blue-900/30 dark:text-blue-200 dark:hover:bg-blue-900/50"
                >
                    {{ __('interventions.actions.today') }}
                </button>
                <button
                    type="button"
                    wire:click="goToNextDay"
                    class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-100 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-800"
                >
                    {{ __('interventions.actions.tomorrow') }}
                </button>
            </div>

            {{ $this->form }}

            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                {{ __('interventions.fields.note_date') }}: {{ $this->selectedDateLabel() }}
            </div>

            @can('Create:Intervention')
                <div class="mt-3 rounded-lg border border-primary-200 bg-primary-50 px-3 py-2 text-xs text-primary-900">
                    Per Gerardo la compilazione rapida parte da qui: scegli la data e usa il pulsante <strong>Nuovo intervento</strong> in alto.
                </div>
            @endcan
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm dark:bg-gray-900">
            <div class="text-sm text-gray-600 dark:text-gray-300">
                <strong>{{ __('interventions.daily_note') }}</strong>
            </div>

            @php($note = $this->dailyNoteForSelectedDate())
            <div class="mt-3">
                @include('interventions::filament.components.daily-note-display', ['note' => $note])
            </div>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm dark:bg-gray-900">
            <div class="text-sm text-gray-600 dark:text-gray-300">
                <strong>{{ __('interventions.interventions') }}</strong>
            </div>

            @php($items = $this->interventionsForSelectedDate())

            @if(count($items) === 0)
                <div class="mt-2 text-sm text-gray-500">{{ __('interventions.no_interventions_for_day') }}</div>
            @else
                <div class="mt-3 space-y-2">
                    @foreach($items as $intervention)
                        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                        <a class="block p-3 hover:bg-gray-50 dark:hover:bg-gray-800"
                           href="{{ \Modules\Intervention\Filament\Resources\InterventionResource::getUrl('view', ['record' => $intervention->id]) }}">
                            <div class="flex items-center justify-between gap-4">
                                <div class="text-sm font-medium">
                                    {{ $intervention->workOrder?->title ?: $intervention->workOrder?->code ?: ('Intervento #' . $intervention->id) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ optional($intervention->started_at)->format('H:i') ?: '-' }}
                                    —
                                    {{ optional($intervention->ended_at)->format('H:i') ?: '-' }}
                                </div>
                            </div>
                            <div class="mt-1 text-xs text-gray-600 dark:text-gray-300">
                                Intervento #{{ $intervention->id }} ·
                                {{ $intervention->workOrder?->customer?->legal_name ?: ($intervention->customer?->legal_name ?: 'Cliente non disponibile') }}
                                @if($intervention->workOrder?->site?->name)
                                    · {{ $intervention->workOrder->site->name }}
                                @endif
                            </div>
                            @if($intervention->notes)
                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Illuminate\Support\Str::limit($intervention->notes, 120) }}
                                </div>
                            @endif
                            <div class="mt-2 text-[11px] font-medium text-primary-700 dark:text-primary-300">
                                Apri scheda intervento
                            </div>
                        </a>
                        @if(\Modules\Intervention\Filament\Pages\RecordInterventionReportPage::canAccess())
                            <a class="flex items-center gap-1 border-t border-gray-100 px-3 py-2 text-[11px] font-semibold text-primary-700 hover:bg-primary-50 dark:border-gray-700 dark:text-primary-300 dark:hover:bg-primary-500/10"
                               href="{{ \Modules\Intervention\Filament\Pages\RecordInterventionReportPage::getUrl(['intervention' => $intervention->id]) }}">
                                🎙️ {{ __('interventions.voice_report.title') }}
                            </a>
                        @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>

