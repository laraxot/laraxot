<x-filament-panels::page>
    <div
        x-data="quotationVoice()"
        x-on:beforeunload.window="stopStream()"
        class="mx-auto flex w-full max-w-xl flex-col gap-6"
    >
        @if ($sourceLabel !== '')
            <div class="rounded-xl bg-primary-50 px-4 py-3 text-sm font-medium text-primary-700 dark:bg-primary-500/10 dark:text-primary-200">
                {{ __('quotations.voice.based_on', ['label' => $sourceLabel]) }}
            </div>
        @endif

        {{-- Recorder --}}
        <div class="flex flex-col items-center gap-4 py-2">
            <button
                type="button"
                x-on:click="toggle()"
                class="voice-mic relative flex h-28 w-28 items-center justify-center rounded-full text-white shadow-lg transition-transform active:scale-95"
                :class="recording ? 'voice-mic--recording bg-danger-500' : 'bg-primary-600 hover:bg-primary-500'"
                :disabled="busy"
                aria-label="{{ __('quotations.voice.record') }}"
            >
                <span class="voice-mic__ring" x-show="recording"></span>
                <x-heroicon-o-microphone class="relative z-10 h-12 w-12" x-show="!recording" />
                <x-heroicon-o-stop class="relative z-10 h-12 w-12" x-show="recording" x-cloak />
            </button>

            <p class="min-h-5 text-center text-sm text-gray-500 dark:text-gray-400" x-text="status"></p>

            <template x-if="transcript">
                <p class="max-w-md rounded-lg bg-gray-100 px-3 py-2 text-center text-xs italic text-gray-500 dark:bg-white/5 dark:text-gray-400" x-text="transcript"></p>
            </template>
        </div>

        @if ($hasDraft)
            <div class="flex flex-col gap-5 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-900">
                {{-- Based on which quotation (or none) --}}
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('quotations.voice.based_on') }}</label>
                    <select wire:model="sourceId" class="w-full rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5">
                        @foreach ($this->sourceOptions() as $optId => $optLabel)
                            <option value="{{ $optId }}">{{ $optLabel }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Customer --}}
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('quotations.voice.customer') }}</label>

                    {{-- Online lookup by business name / VAT --}}
                    <div class="mb-2 flex gap-2">
                        <input type="text" wire:model="companyQuery" wire:keydown.enter.prevent="searchCompany" placeholder="{{ __('quotations.voice.company_search_placeholder') }}" class="min-w-0 flex-1 rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                        <button type="button" wire:click="searchCompany" wire:loading.attr="disabled" wire:target="searchCompany" class="shrink-0 rounded-md bg-gray-100 px-3 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-white/10 dark:text-gray-200">
                            <span wire:loading.remove wire:target="searchCompany">{{ __('quotations.voice.search_online') }}</span>
                            <span wire:loading wire:target="searchCompany">…</span>
                        </button>
                    </div>
                    @if (count($companyCandidates))
                        <div class="mb-2 flex flex-col gap-1">
                            @foreach ($companyCandidates as $ci => $candidate)
                                <button type="button" wire:click="pickCompany({{ $ci }})" wire:key="cand-{{ $ci }}" class="rounded-md border border-gray-200 px-3 py-2 text-left text-xs hover:border-primary-400 hover:bg-primary-50 dark:border-white/10 dark:hover:bg-primary-500/10">
                                    <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $candidate['legal_name'] }}</span>
                                    <span class="text-gray-500 dark:text-gray-400">
                                        {{ $candidate['vat_number'] ? '· '.$candidate['vat_number'] : '' }}
                                        {{ $candidate['city'] ? '· '.$candidate['city'] : '' }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        <input type="text" wire:model="customerLegalName" placeholder="{{ __('quotations.fields.customer') }}" class="rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5 sm:col-span-2" />
                        <input type="text" wire:model="customerVat" placeholder="P.IVA" class="rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                        <input type="text" wire:model="customerTaxCode" placeholder="{{ __('quotations.voice.tax_code') }}" class="rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                        <input type="text" wire:model="customerAddress" placeholder="{{ __('quotations.voice.address') }}" class="rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5 sm:col-span-2" />
                        <input type="text" wire:model="customerPostalCode" placeholder="{{ __('quotations.voice.postal_code') }}" class="rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                        <input type="text" wire:model="customerCity" placeholder="{{ __('quotations.voice.city') }}" class="rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                        <input type="text" wire:model="customerProvince" placeholder="{{ __('quotations.voice.province') }}" class="rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                        <input type="text" wire:model="customerEmail" placeholder="Email" class="rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                        <input type="text" wire:model="customerPhone" placeholder="{{ __('quotations.voice.phone') }}" class="rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('quotations.fields.title') }}</label>
                        <input type="text" wire:model="quotationTitle" class="w-full rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('quotations.fields.tax_percent') }}</label>
                        <input type="number" step="0.01" min="0" wire:model="taxPercent" placeholder="22" class="w-full rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('quotations.fields.notes') }}</label>
                    <textarea wire:model="notes" rows="2" class="w-full rounded-lg border-gray-300 text-sm shadow-sm dark:border-white/10 dark:bg-white/5"></textarea>
                </div>

                {{-- Technical parameters --}}
                @php($paramDefs = $this->technicalParamDefinitions())
                @if (count($paramDefs))
                    <div class="rounded-lg border border-gray-100 p-3 dark:border-white/5">
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('quotations.voice.technical_params') }}</label>
                            <button type="button" wire:click="recalcFromParams" class="text-xs font-medium text-primary-600 hover:text-primary-500">↻ {{ __('quotations.voice.recalc') }}</button>
                        </div>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            @foreach ($paramDefs as $def)
                                <label class="flex items-center justify-between gap-2 text-sm">
                                    <span class="text-gray-600 dark:text-gray-300">{{ __('quotations.voice.params.'.$def['key']) }}</span>
                                    @if ($def['type'] === 'bool')
                                        <select wire:model="technicalParams.{{ $def['key'] }}" class="w-28 rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5">
                                            <option value="">—</option>
                                            <option value="1">{{ __('quotations.voice.present') }}</option>
                                            <option value="0">{{ __('quotations.voice.absent') }}</option>
                                        </select>
                                    @else
                                        <span class="flex items-center gap-1">
                                            <input type="number" step="0.01" min="0" wire:model="technicalParams.{{ $def['key'] }}" class="w-24 rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                            @if ($def['unit'])<span class="text-xs text-gray-400">{{ $def['unit'] }}</span>@endif
                                        </span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                        <p class="mt-2 text-[11px] text-gray-400">{{ __('quotations.voice.recalc_hint') }}</p>
                    </div>
                @endif

                {{-- Items --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('quotations.quotation_items') }}</label>
                        <button type="button" wire:click="addItemRow" class="text-xs font-medium text-primary-600 hover:text-primary-500">+ {{ __('quotations.voice.add_item') }}</button>
                    </div>

                    <div class="flex flex-col gap-2">
                        @forelse ($items as $index => $item)
                            <div class="flex items-center gap-2 rounded-lg border border-gray-100 p-2 dark:border-white/5" wire:key="item-{{ $index }}">
                                <input type="text" wire:model="items.{{ $index }}.description" placeholder="{{ __('quotations.fields.description') }}" class="min-w-0 flex-1 rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                <input type="number" step="0.01" min="0" wire:model="items.{{ $index }}.quantity" class="w-16 rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                <input type="number" step="0.01" min="0" wire:model="items.{{ $index }}.unit_price" placeholder="€" class="w-20 rounded-md border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                @if ($item['is_matched'])
                                    <span class="shrink-0 rounded-full bg-success-100 px-2 py-0.5 text-[10px] font-semibold uppercase text-success-700 dark:bg-success-500/20 dark:text-success-300" title="{{ __('quotations.voice.matched') }}">✓</span>
                                @else
                                    <span class="shrink-0 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold uppercase text-amber-700 dark:bg-amber-500/20 dark:text-amber-300" title="{{ __('quotations.voice.free') }}">€?</span>
                                @endif
                                <button type="button" wire:click="removeItem({{ $index }})" class="shrink-0 text-gray-400 hover:text-danger-500" aria-label="{{ __('quotations.voice.remove_item') }}">
                                    <x-heroicon-o-trash class="h-4 w-4" />
                                </button>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400">{{ __('quotations.voice.no_items') }}</p>
                        @endforelse
                    </div>

                    @if (count($items))
                        @php($itemsTotal = collect($items)->sum(fn ($m) => (float) ($m['quantity'] ?? 0) * (float) ($m['unit_price'] ?? 0)))
                        <div class="mt-2 flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 text-sm font-semibold text-gray-900 dark:bg-white/5 dark:text-gray-100">
                            <span>{{ __('quotations.voice.items_total') }}</span>
                            <span>{{ number_format($itemsTotal, 2, ',', '.') }} €</span>
                        </div>
                    @endif
                </div>

                <button type="button" wire:click="confirm" wire:loading.attr="disabled" class="mt-2 flex w-full items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-3 text-sm font-semibold text-white shadow hover:bg-primary-500 disabled:opacity-60">
                    <x-heroicon-o-check-circle class="h-5 w-5" />
                    {{ __('quotations.voice.confirm') }}
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
            Alpine.data('quotationVoice', () => ({
                recording: false,
                busy: false,
                status: @js(__('quotations.voice.tap_to_record')),
                transcript: '',
                recognition: null,
                accumulated: '',
                wantStop: false,
                mediaRecorder: null,
                chunks: [],
                stream: null,

                get liveSupported() {
                    return 'SpeechRecognition' in window || 'webkitSpeechRecognition' in window;
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

                startLive() {
                    const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                    const rec = new SR();
                    rec.lang = 'it-IT';
                    rec.interimResults = true;
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
                            this.status = @js(__('quotations.voice.mic_denied'));
                        }
                    };
                    rec.onend = () => {
                        if (!this.wantStop) {
                            try { rec.start(); return; } catch (_) {}
                        }
                        this.finishLive();
                    };
                    this.recognition = rec;
                    this.recording = true;
                    this.status = @js(__('quotations.voice.listening'));
                    try { rec.start(); } catch (_) {}
                },

                async finishLive() {
                    this.recording = false;
                    this.recognition = null;
                    const text = this.transcript.trim();
                    if (text === '') { this.status = @js(__('quotations.voice.tap_to_record')); return; }
                    this.busy = true;
                    this.status = @js(__('quotations.voice.generating'));
                    try {
                        await this.$wire.set('transcript', text);
                        await this.$wire.generateDraft();
                        this.status = '';
                    } catch (_) {
                        this.status = @js(__('quotations.voice.transcription_failed'));
                    } finally {
                        this.busy = false;
                    }
                },

                async startRecorder() {
                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    } catch (_) {
                        this.status = @js(__('quotations.voice.mic_denied'));
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
                    this.status = @js(__('quotations.voice.listening'));
                },

                stopStream() {
                    try { this.stream?.getTracks().forEach((t) => t.stop()); } catch (_) {}
                },

                async onStop() {
                    this.recording = false;
                    this.mediaRecorder = null;
                    this.stopStream();
                    this.busy = true;
                    this.status = @js(__('quotations.voice.transcribing'));

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
                            this.status = @js(__('quotations.voice.generating'));
                            await this.$wire.set('transcript', data.text);
                            await this.$wire.generateDraft();
                            this.status = '';
                        } else {
                            this.status = @js(__('quotations.voice.transcription_failed'));
                        }
                    } catch (_) {
                        this.status = @js(__('quotations.voice.transcription_failed'));
                    } finally {
                        this.busy = false;
                    }
                },
            }));
        </script>
    @endscript
</x-filament-panels::page>
