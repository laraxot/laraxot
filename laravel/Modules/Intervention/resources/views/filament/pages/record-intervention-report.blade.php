<x-filament-panels::page>
    @if ($interventionId === null)
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">
            {{ __('interventions.voice_report.no_intervention') }}
        </div>
    @else
        <div
            x-data="voiceReport()"
            x-on:beforeunload.window="stopStream()"
            class="mx-auto flex w-full max-w-xl flex-col gap-6"
        >
            {{-- Intervention context --}}
            <div class="rounded-xl bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 dark:bg-white/5 dark:text-gray-200">
                {{ $interventionLabel }}
            </div>

            {{-- Recorder --}}
            <div class="flex flex-col items-center gap-4 py-2">
                <button
                    type="button"
                    x-on:click="toggle()"
                    class="voice-mic relative flex h-28 w-28 items-center justify-center rounded-full text-white shadow-lg transition-transform active:scale-95"
                    :class="recording ? 'voice-mic--recording bg-danger-500' : 'bg-primary-600 hover:bg-primary-500'"
                    :disabled="busy"
                    aria-label="{{ __('interventions.voice_report.record') }}"
                >
                    <span class="voice-mic__ring" x-show="recording"></span>
                    <x-heroicon-o-microphone class="relative z-10 h-12 w-12" x-show="!recording" />
                    <x-heroicon-o-stop class="relative z-10 h-12 w-12" x-show="recording" x-cloak />
                </button>

                <p class="min-h-5 text-center text-sm text-gray-500 dark:text-gray-400" x-text="status"></p>

                <template x-if="transcript">
                    <p class="max-w-md rounded-lg bg-gray-100 px-3 py-2 text-center text-xs italic text-gray-500 dark:bg-white/5 dark:text-gray-400" x-text="transcript"></p>
                </template>

                {{-- Live guidance: what has been said so far, and what is still
                     missing. Shown only while dictating; once the draft exists
                     the authoritative checklist below takes over. --}}
                @if (! $hasDraft)
                    <template x-if="recording || transcript">
                        <div class="w-full max-w-md">
                            <p class="mb-2 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-400">{{ __('interventions.voice_report.checklist') }}</p>
                            <div class="flex flex-wrap justify-center gap-2">
                                <template x-for="hint in hints" :key="hint.key">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs transition-colors"
                                        :class="hint.ok
                                            ? 'bg-success-100 font-medium text-success-700 dark:bg-success-500/20 dark:text-success-300'
                                            : 'bg-gray-100 text-gray-400 dark:bg-white/5 dark:text-gray-500'"
                                    >
                                        <span x-text="hint.ok ? '✓' : '○'"></span>
                                        <span x-text="{{ Js::from(__('interventions.voice_report.checklist_items')) }}[hint.key]"></span>
                                    </span>
                                </template>
                            </div>
                            <p class="mt-2 text-center text-[11px] text-gray-400">{{ __('interventions.voice_report.checklist_hint') }}</p>
                        </div>
                    </template>
                @endif
            </div>

            {{-- Draft review --}}
            @if ($hasDraft)
                <div class="flex flex-col gap-5 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
                    {{-- What was actually captured. Unlike the live hints above
                         this is not guesswork: it reads the extracted values, so
                         a grey dot means the field really is empty. --}}
                    @php
                        $captured = [
                            'work' => trim($notes) !== '',
                            'minutes' => $workMinutes !== null && $workMinutes > 0,
                            'materials' => count($materials) > 0,
                            'operators' => count($operatorNames) > 0,
                            'outcome' => $outcomeCode !== null,
                            'issues' => count(array_filter($issues, fn ($i) => trim((string) $i) !== '')) > 0,
                        ];
                        $missing = array_keys(array_filter($captured, fn ($ok) => ! $ok));
                    @endphp

                    <div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($captured as $key => $ok)
                                <span @class([
                                    'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs',
                                    'bg-success-100 font-medium text-success-700 dark:bg-success-500/20 dark:text-success-300' => $ok,
                                    'bg-gray-100 text-gray-400 dark:bg-white/5 dark:text-gray-500' => ! $ok,
                                ])>
                                    {{ $ok ? '✓' : '○' }} {{ __('interventions.voice_report.checklist_items.'.$key) }}
                                </span>
                            @endforeach
                        </div>
                        @if ($missing !== [])
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('interventions.voice_report.still_missing', [
                                    'fields' => collect($missing)->map(fn ($k) => __('interventions.voice_report.checklist_items.'.$k))->implode(', '),
                                ]) }}
                            </p>
                        @endif
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('interventions.fields.notes') }}</label>
                        <textarea wire:model="notes" rows="3" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-white/10 dark:bg-white/5"></textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('interventions.fields.work_minutes') }}</label>
                            <input type="number" min="0" wire:model="workMinutes" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-white/10 dark:bg-white/5" />
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('interventions.fields.outcome') }}</label>
                            <select wire:model="outcomeCode" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-white/10 dark:bg-white/5">
                                <option value="">—</option>
                                <option value="completed">{{ __('interventions.outcomes.completed') }}</option>
                                <option value="suspended">{{ __('interventions.outcomes.suspended') }}</option>
                                <option value="quotation">{{ __('interventions.outcomes.quotation') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('interventions.fields.travel_km') }}</label>
                            <input type="number" min="0" wire:model="travelKm" class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-white/10 dark:bg-white/5" />
                        </div>
                    </div>

                    {{-- Who was on the job. Dictated names that match nobody are
                         dropped by the resolver, so the operator sees exactly
                         what will be attached. --}}
                    @if (count($operatorNames))
                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('interventions.voice_report.operators') }}</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($operatorNames as $index => $operator)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-200" wire:key="operator-{{ $index }}">
                                        {{ $operator }}
                                        <button type="button" wire:click="removeOperator({{ $index }})" class="text-gray-400 hover:text-danger-500" aria-label="{{ __('interventions.voice_report.remove_operator') }}">&times;</button>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Faults noticed but not fixed: each line becomes an issue
                         report, which is where the next quotation comes from. --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('interventions.voice_report.issues') }}</label>
                            <button type="button" wire:click="addIssueRow" class="text-xs font-medium text-primary-600 hover:text-primary-500">+ {{ __('interventions.voice_report.add_issue') }}</button>
                        </div>

                        <div class="flex flex-col gap-2">
                            @forelse ($issues as $index => $issue)
                                <div class="flex items-center gap-2" wire:key="issue-{{ $index }}">
                                    <input type="text" wire:model="issues.{{ $index }}" placeholder="{{ __('interventions.voice_report.issue_placeholder') }}" class="min-w-0 flex-1 rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                    <button type="button" wire:click="removeIssue({{ $index }})" class="shrink-0 text-gray-400 hover:text-danger-500" aria-label="{{ __('interventions.voice_report.remove_issue') }}">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400">{{ __('interventions.voice_report.no_issues') }}</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Materials --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('interventions.fields.material_lines') }}</label>
                            <button type="button" wire:click="addMaterialRow" class="text-xs font-medium text-primary-600 hover:text-primary-500">+ {{ __('interventions.voice_report.add_material') }}</button>
                        </div>

                        <div class="flex flex-col gap-2">
                            @forelse ($materials as $index => $material)
                                <div class="flex items-center gap-2 rounded-lg border border-gray-100 p-2 dark:border-white/5" wire:key="material-{{ $index }}">
                                    <input type="text" wire:model="materials.{{ $index }}.description" placeholder="{{ __('interventions.fields.material_description') }}" class="min-w-0 flex-1 rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                    <input type="number" step="0.01" min="0" wire:model="materials.{{ $index }}.quantity" class="w-14 rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                    <input type="text" maxlength="8" wire:model="materials.{{ $index }}.unit" placeholder="{{ __('interventions.fields.unit') }}" class="w-12 rounded-md border-gray-200 text-center text-sm dark:border-white/10 dark:bg-white/5" />
                                    <input type="number" step="0.01" min="0" wire:model="materials.{{ $index }}.unit_price" placeholder="€" class="w-20 rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                    @if ($material['is_matched'])
                                        <span class="shrink-0 rounded-full bg-success-100 px-2 py-0.5 text-[10px] font-semibold uppercase text-success-700 dark:bg-success-500/20 dark:text-success-300" title="{{ __('interventions.voice_report.matched') }}">✓</span>
                                    @else
                                        <span class="shrink-0 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold uppercase text-amber-700 dark:bg-amber-500/20 dark:text-amber-300" title="{{ __('interventions.voice_report.free') }}">€?</span>
                                    @endif
                                    <button type="button" wire:click="removeMaterial({{ $index }})" class="shrink-0 text-gray-400 hover:text-danger-500" aria-label="{{ __('interventions.voice_report.remove_material') }}">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400">{{ __('interventions.voice_report.no_materials') }}</p>
                            @endforelse
                        </div>

                        @php($extrasTotal = collect($materials)->sum(fn ($m) => (float) ($m['quantity'] ?? 0) * (float) ($m['unit_price'] ?? 0)))

                        @if (count($materials))
                            <div class="mt-2 flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 text-sm font-semibold text-gray-900 dark:bg-white/5 dark:text-gray-100">
                                <span>{{ __('interventions.voice_report.extras_total') }}</span>
                                <span>{{ number_format($extrasTotal, 2, ',', '.') }} €</span>
                            </div>
                        @endif

                        {{-- What the intervention is worth, built up in front of the
                             operator. The surcharge line is the one that matters: it is
                             the money a hand-written report quietly loses. --}}
                        @if ($labourNeedsTariff)
                            <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 p-3 text-xs text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">
                                {{ __('interventions.voice_report.no_tariff') }}
                            </div>
                        @endif

                        @if ($labourAmount !== null || $calloutFee !== null || $extrasTotal > 0)
                            <div class="mt-3 rounded-lg border border-primary-100 bg-primary-50/60 p-3 dark:border-primary-500/20 dark:bg-primary-500/10">
                                @if ($labourAmount !== null)
                                    <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-200">
                                        <span class="flex items-center gap-2">
                                            {{ __('interventions.voice_report.labour') }}
                                            @if ($labourSurchargePercent > 0)
                                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">
                                                    +{{ $labourSurchargePercent }}% {{ __('interventions.voice_report.surcharge.'.$labourSurchargeCode) }}
                                                </span>
                                            @endif
                                        </span>
                                        <span class="font-semibold">{{ number_format($labourAmount, 2, ',', '.') }} €</span>
                                    </div>
                                @endif
                                @if ($calloutFee !== null)
                                    <div class="mt-1 flex items-center justify-between text-sm text-gray-700 dark:text-gray-200">
                                        <span>{{ __('interventions.fields.callout_fee') }}</span>
                                        <span class="font-semibold">{{ number_format($calloutFee, 2, ',', '.') }} €</span>
                                    </div>
                                @endif
                                @if ($extrasTotal > 0)
                                    <div class="mt-1 flex items-center justify-between text-sm text-gray-700 dark:text-gray-200">
                                        <span>{{ __('interventions.voice_report.extras_total') }}</span>
                                        <span class="font-semibold">{{ number_format($extrasTotal, 2, ',', '.') }} €</span>
                                    </div>
                                @endif
                                <div class="mt-2 flex items-center justify-between border-t border-primary-200/60 pt-2 text-base font-bold text-gray-900 dark:border-primary-500/20 dark:text-gray-50">
                                    <span>{{ __('interventions.voice_report.intervention_total') }}</span>
                                    <span>{{ number_format(($labourAmount ?? 0) + ($calloutFee ?? 0) + $extrasTotal, 2, ',', '.') }} €</span>
                                </div>
                                @if ($labourSurchargePercent > 0)
                                    <p class="mt-2 text-xs text-amber-700 dark:text-amber-300">{{ __('interventions.voice_report.surcharge_hint') }}</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Photos --}}
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('interventions.voice_report.photos') }}</label>
                        <label class="flex cursor-pointer items-center justify-center gap-2 rounded-lg border border-dashed border-gray-300 px-4 py-3 text-sm text-gray-500 hover:border-primary-400 hover:text-primary-600 dark:border-white/10">
                            <x-heroicon-o-camera class="h-5 w-5" />
                            <span>{{ __('interventions.voice_report.add_photos') }}</span>
                            <input type="file" wire:model="photos" multiple accept="image/*" capture="environment" class="hidden" />
                        </label>
                        @if (count($photos))
                            <p class="mt-1 text-xs font-medium text-success-600 dark:text-success-400">✓ {{ __('interventions.voice_report.photos_selected', ['count' => count($photos)]) }}</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach ($photos as $photo)
                                    @if (is_object($photo) && method_exists($photo, 'temporaryUrl') && $photo->isPreviewable())
                                        <img src="{{ $photo->temporaryUrl() }}" class="h-16 w-16 rounded-lg border border-gray-200 object-cover dark:border-white/10" alt="foto" />
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <div wire:loading wire:target="photos" class="mt-1 text-xs text-gray-400">…</div>
                    </div>

                    {{-- Signature pad --}}
                    <div x-data="signaturePad()">
                        <div class="mb-1 flex items-center justify-between">
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('interventions.voice_report.signature') }}</label>
                            <button type="button" x-on:click="clear()" class="text-xs font-medium text-gray-500 hover:text-danger-500">{{ __('interventions.voice_report.clear_signature') }}</button>
                        </div>
                        <canvas
                            x-ref="pad"
                            class="h-40 w-full touch-none rounded-lg border border-gray-300 bg-white dark:border-white/10"
                        ></canvas>
                        <p class="mt-1 text-center text-[11px] text-gray-400" x-show="empty">{{ __('interventions.voice_report.sign_here') }}</p>
                    </div>

                    <button type="button" wire:click="confirm" wire:loading.attr="disabled" class="mt-2 flex w-full items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-3 text-sm font-semibold text-white shadow hover:bg-primary-500 disabled:opacity-60">
                        <x-heroicon-o-check-circle class="h-5 w-5" />
                        {{ __('interventions.voice_report.confirm') }}
                    </button>
                </div>
            @endif
        </div>

        @push('styles')
            <style>
                [x-cloak] { display: none !important; }
                .voice-mic__ring {
                    position: absolute;
                    inset: 0;
                    border-radius: 9999px;
                    border: 3px solid rgb(239 68 68 / 0.6);
                    animation: voicePulse 1.4s ease-out infinite;
                }
                .voice-mic--recording { animation: voiceGlow 1.4s ease-in-out infinite; }
                @keyframes voicePulse {
                    0%   { transform: scale(1);   opacity: 0.8; }
                    100% { transform: scale(1.6); opacity: 0; }
                }
                @keyframes voiceGlow {
                    0%, 100% { box-shadow: 0 0 0 0 rgb(239 68 68 / 0.5); }
                    50%      { box-shadow: 0 0 0 14px rgb(239 68 68 / 0); }
                }
            </style>
        @endpush

        @script
            <script>
                Alpine.data('signaturePad', () => ({
                    empty: true,
                    drawing: false,
                    ctx: null,
                    init() {
                        const canvas = this.$refs.pad;
                        const ratio = window.devicePixelRatio || 1;
                        const rect = canvas.getBoundingClientRect();
                        canvas.width = rect.width * ratio;
                        canvas.height = rect.height * ratio;
                        this.ctx = canvas.getContext('2d');
                        this.ctx.scale(ratio, ratio);
                        this.ctx.lineWidth = 2.2;
                        this.ctx.lineCap = 'round';
                        this.ctx.strokeStyle = '#111827';

                        const pos = (e) => {
                            const r = canvas.getBoundingClientRect();
                            const p = e.touches ? e.touches[0] : e;
                            return { x: p.clientX - r.left, y: p.clientY - r.top };
                        };
                        const start = (e) => { e.preventDefault(); this.drawing = true; this.empty = false; const { x, y } = pos(e); this.ctx.beginPath(); this.ctx.moveTo(x, y); };
                        const move = (e) => { if (!this.drawing) return; e.preventDefault(); const { x, y } = pos(e); this.ctx.lineTo(x, y); this.ctx.stroke(); };
                        const end = () => { if (!this.drawing) return; this.drawing = false; this.$wire.set('signature', canvas.toDataURL('image/png')); };

                        canvas.addEventListener('pointerdown', start);
                        canvas.addEventListener('pointermove', move);
                        window.addEventListener('pointerup', end);
                    },
                    clear() {
                        this.ctx?.clearRect(0, 0, this.$refs.pad.width, this.$refs.pad.height);
                        this.empty = true;
                        this.$wire.set('signature', null);
                    },
                }));

                Alpine.data('voiceReport', () => ({
                    recording: false,
                    busy: false,
                    status: @js(__('interventions.voice_report.tap_to_record')),
                    transcript: '',
                    // Live speech recognition (shows text while speaking).
                    recognition: null,
                    accumulated: '',
                    wantStop: false,
                    // MediaRecorder + Whisper fallback.
                    mediaRecorder: null,
                    chunks: [],
                    stream: null,

                    get liveSupported() {
                        return 'SpeechRecognition' in window || 'webkitSpeechRecognition' in window;
                    },

                    /**
                     * Lights up what has already been said while the operator is
                     * still speaking. These are keyword hints, not the real
                     * extraction: the LLM decides what actually ends up in the
                     * report, and the checklist below the draft shows that.
                     * Their job is to teach the shape of a good dictation in a
                     * few days, without anybody reading a manual.
                     */
                    get hints() {
                        const t = ' ' + this.transcript.toLowerCase() + ' ';
                        const has = (re) => re.test(t);

                        return [
                            { key: 'work', ok: this.transcript.trim().split(/\s+/).length >= 5 },
                            { key: 'minutes', ok: has(/\b(minut|or[ae]\b|un'?ora|mezz'?ora|giornat)/) },
                            { key: 'materials', ok: has(/\b(messo|usato|montato|installato|sostituit|cambiat|posato)/) },
                            { key: 'operators', ok: has(/\b(eravamo|insieme a|con il collega|io e )/) },
                            { key: 'outcome', ok: has(/\b(conclus|finit|complet|sospes|da finire|preventiv|sopralluog)/) },
                            { key: 'issues', ok: has(/\b(ho visto|da rifare|andrebbe|da sistemare|perde|vecchi|ha \w+ anni)/) },
                        ];
                    },

                    toggle() {
                        if (this.recording || this.busy) { this.stop(); return; }
                        if (this.liveSupported) { this.startLive(); } else { this.startRecorder(); }
                    },

                    stop() {
                        if (this.recognition) {
                            this.wantStop = true;
                            try { this.recognition.stop(); } catch (_) {}
                            return;
                        }
                        if (this.mediaRecorder) { try { this.mediaRecorder.stop(); } catch (_) {} }
                    },

                    // ── Live recognition ────────────────────────────────
                    startLive() {
                        const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                        const rec = new SR();
                        rec.lang = 'it-IT';
                        rec.interimResults = true;
                        // continuous=false avoids the Android bug where growing
                        // hypotheses pile up as separate results; we restart the
                        // recognition on each pause and keep only final phrases.
                        rec.continuous = false;
                        rec.maxAlternatives = 1;
                        this.accumulated = '';
                        this.transcript = '';
                        this.wantStop = false;
                        rec.onresult = (e) => {
                            let interim = '';
                            for (let i = 0; i < e.results.length; i++) {
                                const res = e.results[i];
                                if (res.isFinal) this.accumulated += res[0].transcript + ' ';
                                else interim += res[0].transcript + ' ';
                            }
                            this.transcript = (this.accumulated + interim).replace(/\s+/g, ' ').trim();
                        };
                        rec.onerror = (ev) => {
                            if (ev && ev.error === 'not-allowed') {
                                this.wantStop = true;
                                this.status = @js(__('interventions.voice_report.mic_denied'));
                            }
                        };
                        rec.onend = () => {
                            // Keep listening across pauses until the user stops.
                            if (!this.wantStop) {
                                try { rec.start(); return; } catch (_) {}
                            }
                            this.finishLive();
                        };
                        this.recognition = rec;
                        this.recording = true;
                        this.status = @js(__('interventions.voice_report.listening'));
                        try { rec.start(); } catch (_) {}
                    },

                    async finishLive() {
                        this.recording = false;
                        this.recognition = null;
                        const text = this.transcript.trim();
                        if (text === '') { this.status = @js(__('interventions.voice_report.tap_to_record')); return; }
                        this.busy = true;
                        this.status = @js(__('interventions.voice_report.generating'));
                        try {
                            await this.$wire.set('transcript', text);
                            await this.$wire.generateDraft();
                            this.status = '';
                        } catch (_) {
                            this.status = @js(__('interventions.voice_report.transcription_failed'));
                        } finally {
                            this.busy = false;
                        }
                    },

                    // ── MediaRecorder + Whisper fallback ────────────────
                    async startRecorder() {
                        try {
                            this.stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        } catch (_) {
                            this.status = @js(__('interventions.voice_report.mic_denied'));
                            return;
                        }
                        this.chunks = [];
                        this.transcript = '';
                        this.mediaRecorder = new MediaRecorder(this.stream);
                        this.mediaRecorder.addEventListener('dataavailable', (e) => {
                            if (e.data && e.data.size > 0) this.chunks.push(e.data);
                        });
                        this.mediaRecorder.addEventListener('stop', () => this.onStop());
                        this.mediaRecorder.start();
                        this.recording = true;
                        this.status = @js(__('interventions.voice_report.listening'));
                    },

                    stopStream() {
                        try { this.stream?.getTracks().forEach((t) => t.stop()); } catch (_) {}
                    },

                    async onStop() {
                        this.recording = false;
                        this.mediaRecorder = null;
                        this.stopStream();
                        this.busy = true;
                        this.status = @js(__('interventions.voice_report.transcribing'));

                        const blob = new Blob(this.chunks, { type: this.chunks[0]?.type || 'audio/webm' });
                        const form = new FormData();
                        form.append('audio', blob, 'audio.webm');
                        form.append('lang', 'it-IT');

                        try {
                            const res = await fetch('/aiassistant/stt', {
                                method: 'POST',
                                body: form,
                                credentials: 'same-origin',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                                    'Accept': 'application/json',
                                },
                            });
                            const data = await res.json();
                            if (data?.ok && data.text) {
                                this.transcript = data.text;
                                this.status = @js(__('interventions.voice_report.generating'));
                                await this.$wire.set('transcript', data.text);
                                await this.$wire.generateDraft();
                                this.status = '';
                            } else {
                                this.status = @js(__('interventions.voice_report.transcription_failed'));
                            }
                        } catch (_) {
                            this.status = @js(__('interventions.voice_report.transcription_failed'));
                        } finally {
                            this.busy = false;
                        }
                    },
                }));
            </script>
        @endscript
    @endif
</x-filament-panels::page>
