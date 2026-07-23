<x-filament-panels::page>
    {{-- FullCalendar v6 via Vite (offline-friendly) --}}
    @push('scripts')
        @vite('resources/js/intervention-calendar.js')
    @endpush

    @push('styles')
        <style>
            /* Event chip: show the intervention text, but bounded so columns stay compact. */
            .gc-ev { display: flex; flex-direction: column; line-height: 1.15; padding: 1px 3px; overflow: hidden; }
            .gc-ev-time { font-weight: 600; font-size: 0.7rem; opacity: 0.85; }
            .gc-ev-title {
                font-size: 0.72rem;
                overflow: hidden;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                white-space: normal;
            }
            .fc-daygrid-event, .fc-daygrid-event .fc-event-main { white-space: normal; }
            .fc-daygrid-dot-event { align-items: flex-start; }
            .fc-list-event .gc-ev { flex-direction: row; gap: .5rem; align-items: baseline; }
            .fc-list-event .gc-ev-title { -webkit-line-clamp: unset; }
            @media (max-width: 640px) {
                .fc-daygrid-event .gc-ev-title,
                .fc-timegrid-event .gc-ev-title { -webkit-line-clamp: 1; white-space: nowrap; text-overflow: ellipsis; }
                .fc-daygrid-event .gc-ev-time,
                .fc-timegrid-event .gc-ev-time { font-size: 0.62rem; }
            }
        </style>
    @endpush

    <div
        x-data="interventionCalendar(@js($this->getOperatorLegend()))"
        x-init="init()"
        class="space-y-4"
    >
        {{-- Legenda operatori con filtro --}}
        <div class="flex flex-wrap items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 shadow-sm">
            <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Operatori</span>
            <template x-for="op in operators" :key="op.id">
                <button
                    type="button"
                    @click="toggleOperator(op.id)"
                    :class="hiddenOperators.includes(op.id) ? 'opacity-40' : ''"
                    class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs font-medium text-gray-700 shadow-sm transition hover:shadow"
                >
                    <span class="h-2.5 w-2.5 rounded-full flex-shrink-0" :style="'background-color:' + op.color"></span>
                    <span x-text="op.name"></span>
                </button>
            </template>
        </div>

        {{-- Calendario --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <div id="gc-intervention-calendar" wire:ignore></div>
        </div>

        {{-- Modale dettaglio --}}
        @if ($showModal && count($selectedIntervention) > 0)
            <div
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                wire:key="intervention-modal-{{ $selectedIntervention['id'] }}"
            >
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeModal"></div>
                <div class="relative w-full max-w-lg rounded-2xl border border-gray-200 bg-white shadow-2xl">
                    <div class="flex items-start justify-between border-b border-gray-100 px-6 py-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Intervento #{{ $selectedIntervention['id'] }}</p>
                            <h2 class="mt-0.5 text-base font-semibold text-gray-900">{{ $selectedIntervention['work_order_title'] }}</h2>
                        </div>
                        <button type="button" wire:click="closeModal" class="ml-4 rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                            <x-heroicon-o-x-mark class="h-5 w-5"/>
                        </button>
                    </div>

                    <div class="divide-y divide-gray-100 px-6 py-2">
                        <div class="grid grid-cols-2 gap-x-4 py-3">
                            <div>
                                <p class="text-xs text-gray-400">Cliente</p>
                                <p class="text-sm font-medium text-gray-900">{{ $selectedIntervention['customer'] }}</p>
                            </div>
                            @if($selectedIntervention['site'])
                                <div>
                                    <p class="text-xs text-gray-400">Sede / Sito</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $selectedIntervention['site'] }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 py-3">
                            <div>
                                <p class="text-xs text-gray-400">Operatore</p>
                                <div class="mt-0.5 flex items-center gap-1.5">
                                    <span class="h-2.5 w-2.5 rounded-full flex-shrink-0" style="background-color: {{ $selectedIntervention['operator_color'] }}"></span>
                                    <p class="text-sm font-medium text-gray-900">{{ $selectedIntervention['operator'] }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Pianificato</p>
                                <p class="text-sm font-medium text-gray-900">{{ $selectedIntervention['scheduled_at'] }}</p>
                            </div>
                        </div>
                        @if($selectedIntervention['notes'])
                            <div class="py-3">
                                <p class="text-xs text-gray-400">Note / Descrizione</p>
                                <p class="mt-1 text-sm text-gray-700 whitespace-pre-line">{{ $selectedIntervention['notes'] }}</p>
                            </div>
                        @endif
                        <div class="grid grid-cols-2 gap-x-4 py-3">
                            @if($selectedIntervention['outcome'])
                                <div>
                                    <p class="text-xs text-gray-400">Esito</p>
                                    <span class="mt-1 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                        {{ $selectedIntervention['outcome'] === 'completed' ? 'bg-green-100 text-green-700' : ($selectedIntervention['outcome'] === 'suspended' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600') }}">
                                        {{ match($selectedIntervention['outcome']) {
                                            'completed' => 'Completato',
                                            'suspended' => 'Sospeso',
                                            'quotation' => 'Preventivo',
                                            default => $selectedIntervention['outcome'],
                                        } }}
                                    </span>
                                </div>
                            @endif
                            @if($selectedIntervention['amount'])
                                <div>
                                    <p class="text-xs text-gray-400">Importo</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $selectedIntervention['amount'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 px-6 py-3">
                        <button type="button" wire:click="closeModal"
                                class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200">
                            Chiudi
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
    function interventionCalendar(operators) {
        return {
            calendar: null,
            operators: operators,
            hiddenOperators: [],

            init() {
                const el = document.getElementById('gc-intervention-calendar');
                if (!el) return;

                this.calendar = new FullCalendar.Calendar(el, {
                    plugins: [
                        FullCalendar.dayGridPlugin,
                        FullCalendar.timeGridPlugin,
                        FullCalendar.listPlugin,
                        FullCalendar.interactionPlugin,
                    ],
                    locales: FullCalendar.allLocales,
                    initialView: window.matchMedia('(max-width: 640px)').matches ? 'listWeek' : 'dayGridMonth',
                    locale: 'it',
                    firstDay: 1,
                    height: 'auto',
                    dayMaxEvents: true,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                    },
                    buttonText: {
                        today: 'Oggi',
                        month: 'Mese',
                        week: 'Settimana',
                        day: 'Giorno',
                        list: 'Lista',
                    },
                    events: (info, successCallback, failureCallback) => {
                        this.$wire.getCalendarEvents(info.startStr, info.endStr)
                            .then(events => {
                                const filtered = events.filter(e =>
                                    !this.hiddenOperators.includes(String(e.extendedProps.operator_id))
                                );
                                successCallback(filtered);
                            })
                            .catch(failureCallback);
                    },
                    eventContent: (arg) => {
                        const p = arg.event.extendedProps || {};
                        const wrapper = document.createElement('div');
                        wrapper.className = 'gc-ev';
                        if (arg.timeText) {
                            const t = document.createElement('span');
                            t.className = 'gc-ev-time';
                            t.textContent = arg.timeText;
                            wrapper.appendChild(t);
                        }
                        const title = document.createElement('span');
                        title.className = 'gc-ev-title';
                        const wo = (p.work_order_title && p.work_order_title !== '—') ? p.work_order_title : '';
                        const parts = [wo, p.customer, p.site].filter((x) => x && x !== '—');
                        title.textContent = parts.join(' · ') || arg.event.title;
                        wrapper.appendChild(title);
                        return { domNodes: [wrapper] };
                    },
                    eventDidMount: (info) => {
                        window.InterventionCalendarTheme.applyEventAppearance(info);
                        info.el.title =
                            (info.event.extendedProps.customer || '') +
                            (info.event.extendedProps.site ? ' – ' + info.event.extendedProps.site : '') +
                            '\n' + (info.event.extendedProps.operator_name || '') +
                            (info.event.extendedProps.scheduled_at ? '\n🕐 ' + info.event.extendedProps.scheduled_at : '');
                    },
                    eventClick: (info) => {
                        this.$wire.openInterventionDetail(info.event.id);
                    },
                });

                this.calendar.render();
            },

            toggleOperator(id) {
                const strId = String(id);
                if (this.hiddenOperators.includes(strId)) {
                    this.hiddenOperators = this.hiddenOperators.filter(o => o !== strId);
                } else {
                    this.hiddenOperators.push(strId);
                }
                this.calendar?.refetchEvents();
            },
        };
    }
    </script>
</x-filament-panels::page>

