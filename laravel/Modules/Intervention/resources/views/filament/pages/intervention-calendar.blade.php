<x-filament-panels::page>
    {{-- FullCalendar v6 via Vite (offline-friendly) --}}
    @push('scripts')
        @vite('resources/js/intervention-calendar.js')
    @endpush

    @push('styles')
        <style>
            @media (max-width: 640px) {
                .fc .fc-toolbar.fc-header-toolbar { flex-wrap: wrap; gap: 0.4rem 0.5rem; margin-bottom: 0.6rem; }
                .fc .fc-toolbar-chunk { display: flex; align-items: center; }
                .fc .fc-toolbar-title { font-size: 1.05rem; line-height: 1.2; white-space: nowrap; }
                .fc .fc-button { padding: 0.3rem 0.55rem; font-size: 0.78rem; }
                .fc .fc-footer-toolbar.fc-toolbar { margin-top: 0.7rem; justify-content: center; }
                .fc .fc-col-header-cell-cushion { font-size: 0.7rem; padding: 2px 1px; }
                .fc .fc-timegrid-slot-label-cushion,
                .fc .fc-timegrid-axis-cushion { font-size: 0.68rem; }
                .fc .fc-daygrid-day-number { font-size: 0.72rem; padding: 2px 3px; }
                .fc .fc-daygrid-event { font-size: 0.68rem; padding: 0 2px; }
            }

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

            /* List/agenda view: the row is wide, show the whole text. */
            .fc-list-event .gc-ev { flex-direction: row; gap: .5rem; align-items: baseline; }
            .fc-list-event .gc-ev-title { -webkit-line-clamp: unset; }

            /* On phones keep month/week chips to a single line with an ellipsis. */
            @media (max-width: 640px) {
                .fc-daygrid-event .gc-ev-title,
                .fc-timegrid-event .gc-ev-title {
                    -webkit-line-clamp: 1;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                }
                .fc-daygrid-event .gc-ev-time,
                .fc-timegrid-event .gc-ev-time { font-size: 0.62rem; }
            }
        </style>
    @endpush

    <div
        x-data="interventionCalendar(@js($this->getOperatorLegend()), @js($this->getCreateInterventionUrl() !== ''))"
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

        @if ($this->getCreateInterventionUrl() !== '')
            <div class="rounded-xl border border-primary-200 bg-primary-50 px-4 py-3 text-sm text-primary-900 shadow-sm">
                Da qui puoi consultare il calendario giornaliero, settimanale e mensile; puoi anche cliccare direttamente su una data o su un'ora per aprire subito il nuovo intervento.
            </div>
        @endif

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
                    {{-- Header --}}
                    <div class="flex items-start justify-between border-b border-gray-100 px-6 py-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Intervento #{{ $selectedIntervention['id'] }}</p>
                            @if(($selectedIntervention['work_order_title'] ?? '—') !== '—')
                                <h2 class="mt-0.5 text-base font-semibold text-gray-900">{{ $selectedIntervention['work_order_title'] }}</h2>
                            @endif
                        </div>
                        <button type="button" wire:click="closeModal" class="ml-4 rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                            <x-heroicon-o-x-mark class="h-5 w-5"/>
                        </button>
                    </div>

                    {{-- Body --}}
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
                                    <svg class="h-2.5 w-2.5 flex-shrink-0" viewBox="0 0 10 10" aria-hidden="true">
                                        <circle cx="5" cy="5" r="5" fill="{{ $selectedIntervention['operator_color'] }}" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-900">{{ $selectedIntervention['operator'] }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Pianificato</p>
                                <p class="text-sm font-medium text-gray-900">{{ $selectedIntervention['scheduled_at'] }}</p>
                            </div>
                        </div>
                        @if($selectedIntervention['started_at'] !== '—')
                        <div class="grid grid-cols-2 gap-x-4 py-3">
                            <div>
                                <p class="text-xs text-gray-400">Inizio effettivo</p>
                                <p class="text-sm text-gray-700">{{ $selectedIntervention['started_at'] }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Fine effettiva</p>
                                <p class="text-sm text-gray-700">{{ $selectedIntervention['ended_at'] }}</p>
                            </div>
                        </div>
                        @endif
                        @if($selectedIntervention['notes'])
                        <div class="py-3">
                            <p class="text-xs text-gray-400">Note / Descrizione</p>
                            <p class="mt-1 text-sm text-gray-700 whitespace-pre-line">{{ $selectedIntervention['notes'] }}</p>
                        </div>
                        @endif
                        <div class="grid grid-cols-2 gap-x-4 py-3">
                            @if($selectedIntervention['outcome'])
                            @php
                                $outcomeBadgeClasses = match($selectedIntervention['outcome']) {
                                    'completed' => 'bg-green-100 text-green-700',
                                    'suspended' => 'bg-orange-100 text-orange-700',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <div>
                                <p class="text-xs text-gray-400">Esito</p>
                                <span class="mt-1 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $outcomeBadgeClasses }}">
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

                    {{-- Footer --}}
                    <div class="flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 px-6 py-3">
                        <div class="flex flex-wrap items-center gap-2">
                            @if(\Modules\Intervention\Filament\Pages\RecordInterventionReportPage::canAccess())
                                <a
                                    href="{{ \Modules\Intervention\Filament\Pages\RecordInterventionReportPage::getUrl(['intervention' => $selectedIntervention['id']]) }}"
                                    class="inline-flex items-center gap-1 rounded-xl bg-primary-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-500"
                                >
                                    🎙️ {{ __('interventions.voice_report.title') }}
                                </a>
                            @endif
                            <a
                                href="{{ $selectedIntervention['view_url'] ?? \Modules\Intervention\Filament\Resources\InterventionResource::getUrl('view', ['record' => $selectedIntervention['id']]) }}"
                                class="rounded-xl border border-primary-200 bg-primary-50 px-4 py-2 text-sm font-medium text-primary-700 transition hover:bg-primary-100"
                            >
                                Apri scheda intervento
                            </a>
                        </div>
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
    function interventionCalendar(operators, canCreateIntervention) {
        return {
            calendar: null,
            operators: operators,
            canCreateIntervention: canCreateIntervention,
            hiddenOperators: [],
            lastDateClickKey: null,
            lastDateClickAt: 0,

            currentCalendarState() {
                if (!this.calendar) {
                    return { view: 'dayGridMonth', date: null };
                }

                return {
                    view: this.calendar.view.type,
                    date: this.calendar.getDate().toISOString().slice(0, 10),
                };
            },

            syncCalendarUrl() {
                if (!this.calendar) return;

                const state = this.currentCalendarState();
                const url = new URL(window.location.href);
                url.searchParams.set('calendar_view', state.view);
                url.searchParams.set('calendar_date', state.date);
                window.history.replaceState({}, '', url);
            },

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
                    initialView: new URLSearchParams(window.location.search).get('calendar_view')
                        || (window.matchMedia('(max-width: 640px)').matches ? 'listWeek' : 'dayGridMonth'),
                    initialDate: new URLSearchParams(window.location.search).get('calendar_date') || undefined,
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
                        const state = this.currentCalendarState();
                        this.$wire.openInterventionDetail(info.event.id, state.view, state.date);
                    },
                    dateClick: (info) => {
                        const state = this.currentCalendarState();

                        if (state.view === 'dayGridMonth') {
                            if (!this.canCreateIntervention) return;

                            this.$wire.getCreateInterventionUrl(info.dateStr, true, state.view, state.date)
                                .then((url) => {
                                    if (typeof url === 'string' && url.trim() !== '') {
                                        window.location.assign(url);
                                    }
                                });
                            return;
                        }

                        if (!this.canCreateIntervention) return;

                        this.$wire.getCreateInterventionUrl(info.dateStr, info.allDay, state.view, state.date)
                            .then((url) => {
                                if (typeof url === 'string' && url.trim() !== '') {
                                    window.location.assign(url);
                                }
                            });
                    },
                    datesSet: () => {
                        this.syncCalendarUrl();
                    },
                    windowResize: () => {
                        this.applyResponsiveToolbar();
                    },
                });

                this.calendar.render();
                this.applyResponsiveToolbar();

                this.$wire.on('closeModal', () => {});
            },

            applyResponsiveToolbar() {
                if (!this.calendar) return;
                const mobile = window.innerWidth < 640;

                if (mobile) {
                    this.calendar.setOption('headerToolbar', {
                        left: 'prev,next',
                        center: 'title',
                        right: 'today',
                    });
                    this.calendar.setOption('footerToolbar', {
                        center: 'dayGridMonth,timeGridWeek,listWeek',
                    });
                    this.calendar.setOption('titleFormat', { year: 'numeric', month: 'short' });
                } else {
                    this.calendar.setOption('headerToolbar', {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                    });
                    this.calendar.setOption('footerToolbar', false);
                    this.calendar.setOption('titleFormat', { year: 'numeric', month: 'long' });
                }
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
