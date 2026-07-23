@php
    $contextInterventionId = $this->contextInterventionId;
    $contextWarehouseId = $this->contextWarehouseId;
    $contextInterventionLabel = $this->contextInterventionLabel;
    $contextWarehouseLabel = $this->contextWarehouseLabel;
    $panelId = $this->panelId;
    $exampleGroups = $this->exampleGroups;
    $quickPrompts = $this->quickPrompts;
    $materialsOnInterventionsEnabled = $this->materialsOnInterventionsEnabled;
    $pendingCommunicationProposals = $this->pendingCommunicationProposals();
    $canViewCommunicationsMonitor = $this->canViewCommunicationsMonitor();
    $pendingCommunicationCount = $this->pendingCommunicationCount();
    $chatThreads = $this->chatThreads();
    $messagesMonitorUrl = $canViewCommunicationsMonitor ? \Modules\AiAssistant\Filament\Pages\AiCommunicationsMonitor::getUrl() : null;
    $currentThreadPublicId = $this->currentThreadPublicId;
    $currentThreadLoadedLabel = $this->currentThreadLoadedLabel;
    $initialMessages = $this->initialMessages;
@endphp

<x-filament-panels::page>
    <div class="rounded-none border-0 bg-white shadow-none dark:bg-gray-950 sm:rounded-3xl sm:border sm:border-gray-200 sm:shadow-sm sm:dark:border-gray-800">
        <div class="p-2 sm:p-7">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="hidden min-w-0 sm:block">
                    <div class="inline-flex items-center gap-2 rounded-full bg-gray-900/5 px-3 py-1 text-xs font-semibold text-gray-700 ring-1 ring-gray-900/10 dark:bg-white/10 dark:text-gray-200 dark:ring-white/15">
                        <x-heroicon-o-cpu-chip class="h-4 w-4 text-primary-600 dark:text-primary-400" />
                        Assistente AI
                    </div>
                    <div class="mt-3 text-balance text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                        {{ $panelId === 'admin'
                            ? 'Una chat amministrativa, pensata per lavorare per cliente, commessa e documento.'
                            : 'Una chat operativa, pensata per lavorare per cliente, commessa e intervento.' }}
                    </div>
                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        Le consultazioni rispondono subito. Se invece devo modificare dati, preparo prima una proposta chiara e decidi tu.
                        <span class="text-gray-500 dark:text-gray-400">Scorciatoia: Ctrl/⌘ + Invio.</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <div id="ai-status" class="hidden rounded-full bg-gray-900/5 px-3 py-1 text-xs font-semibold text-gray-700 ring-1 ring-gray-900/10 dark:bg-white/10 dark:text-gray-200 dark:ring-white/15"></div>

                    <a href="{{ \Modules\AiAssistant\Filament\Pages\AiAssistantChat::getUrl(['new' => 1]) }}"
                       class="inline-flex items-center gap-1 rounded-xl bg-primary-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-primary-500">
                        <x-heroicon-o-plus class="h-4 w-4" /> <span class="hidden sm:inline">Nuova chat</span>
                    </a>

                    @if(count($chatThreads) > 0)
                        <select
                            onchange="if(this.value){window.location=this.value}"
                            class="max-w-[9rem] rounded-xl border-gray-200 bg-white py-2 text-xs font-semibold text-gray-700 shadow-sm dark:border-gray-800 dark:bg-gray-950 dark:text-gray-200"
                            title="Chat precedenti"
                        >
                            <option value="">🕘 Chat precedenti</option>
                            @foreach($chatThreads as $thread)
                                <option value="{{ \Modules\AiAssistant\Filament\Pages\AiAssistantChat::getUrl(['thread' => $thread['public_id']]) }}" @selected($thread['active'])>{{ $thread['label'] }}</option>
                            @endforeach
                        </select>
                    @endif

                    <button
                        type="button"
                        id="ai-voice-toggle"
                        aria-pressed="false"
                        title="Risposta a voce"
                        class="inline-flex items-center gap-1 rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-500 shadow-sm transition hover:-translate-y-0.5 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-950 dark:text-gray-400 dark:hover:bg-gray-900"
                    >
                        🔇 Voce off
                    </button>
                    <button
                        type="button"
                        id="ai-clear"
                        class="hidden"
                    ></button>
                </div>
            </div>

            @if($messagesMonitorUrl && $pendingCommunicationCount > 0)
                <a href="{{ $messagesMonitorUrl }}" class="mt-3 flex items-center justify-between gap-2 rounded-xl bg-amber-50 px-4 py-2.5 text-sm font-medium text-amber-900 ring-1 ring-amber-200 transition hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-200 dark:ring-amber-500/30">
                    <span class="flex items-center gap-2">
                        <x-heroicon-o-chat-bubble-left-right class="h-4 w-4" />
                        {{ $pendingCommunicationCount }} messaggi da approvare
                    </span>
                    <span class="text-xs font-semibold">Vai a Messaggi AI →</span>
                </a>
            @endif

            @if($contextInterventionId)
                <div class="mt-5 rounded-2xl border border-primary-200 bg-primary-50/80 px-4 py-3 text-sm text-primary-900 dark:border-primary-900/40 dark:bg-primary-950/40 dark:text-primary-100">
                    Contesto attivo:
                    <span class="font-semibold">{{ $contextInterventionLabel ?: ('intervento #' . $contextInterventionId) }}</span>
                    @if($contextWarehouseId)
                        dal magazzino/furgone <span class="font-semibold">{{ $contextWarehouseLabel ?: ('#' . $contextWarehouseId) }}</span>
                    @endif
                    .
                    @if($materialsOnInterventionsEnabled)
                        Puoi parlare in modo naturale: cliente, sede, commessa, ultimo intervento aperto, materiale o furgone.
                    @else
                        Puoi parlare in modo naturale per note e chiusura intervento. La gestione materiali su interventi al momento e disabilitata dalle impostazioni.
                    @endif
                </div>
            @endif

            <div class="mt-3 hidden flex-wrap gap-2 sm:flex">
                @foreach($quickPrompts as $quickPrompt)
                    <button type="button" data-ai-prompt="{{ $quickPrompt['prompt'] }}" class="rounded-full bg-white/70 px-3 py-1 text-xs font-semibold text-gray-800 ring-1 ring-gray-900/10 backdrop-blur transition hover:-translate-y-0.5 hover:bg-white dark:bg-white/10 dark:text-gray-100 dark:ring-white/15">
                        {{ $quickPrompt['label'] }}
                    </button>
                @endforeach
            </div>

            @if(count($exampleGroups) > 0)
                <div class="mt-4 hidden max-w-xl sm:block">
                    <label for="ai-example-select" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Esempi rapidi
                    </label>
                    <select id="ai-example-select" class="w-full rounded-2xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-800 dark:bg-gray-950 dark:text-gray-100">
                        <option value="">Scegli un esempio da inserire in chat…</option>
                        @foreach($exampleGroups as $groupLabel => $examples)
                            <optgroup label="{{ $groupLabel }}">
                                @foreach($examples as $example)
                                    <option value="{{ $example }}">{{ $example }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            @endif

            <div
                id="ai-context"
                data-intervention-id="{{ $contextInterventionId }}"
                data-warehouse-id="{{ $contextWarehouseId }}"
                data-panel-id="{{ $panelId }}"
                data-thread-public-id="{{ $currentThreadPublicId }}"
            ></div>
            <script type="application/json" id="ai-initial-messages">@json($initialMessages)</script>

            <div class="mt-2 sm:mt-6">
                <div class="flex w-full flex-col rounded-2xl border border-gray-200 bg-white/70 ring-1 ring-gray-900/5 backdrop-blur dark:border-gray-800 dark:bg-gray-950/60 dark:ring-white/10">
                    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                        <div class="flex items-center gap-2">
                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">Chat</div>
                            <div class="rounded-full bg-gray-900/5 px-2 py-0.5 text-[11px] font-medium text-gray-500 dark:bg-white/10 dark:text-gray-400">
                                {{ $currentThreadLoadedLabel ?: 'Nuova chat' }}
                            </div>
                        </div>
                        <div class="hidden text-xs text-gray-500 dark:text-gray-400 sm:block">
                            <button type="button" id="ai-help" class="rounded-full px-2 py-1 font-semibold hover:bg-white/10">
                                Come funziona
                            </button>
                        </div>
                    </div>

                    <div id="ai-help-panel" class="hidden border-b border-gray-200 px-4 py-3 text-sm text-gray-700 dark:border-gray-800 dark:text-gray-200">
                        Dimmi cosa vuoi ottenere usando parole normali: cliente, sede, commessa, ultimo intervento, materiale, furgone.
                        Se stai solo chiedendo informazioni, rispondo subito.
                        Se invece devo modificare dati, preparo una <span class="font-semibold">proposta</span> e tu la confermi direttamente dentro la chat.
                    </div>

                    <div id="ai-messages" class="h-[calc(100dvh-22rem)] min-h-[16rem] space-y-4 overflow-y-auto px-4 py-4 sm:h-[calc(100vh-24rem)] sm:min-h-[24rem]">
                        @if(false)
                            <div class="flex items-start gap-2">
                                <div class="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-600/10 text-primary-700 ring-1 ring-primary-600/20 dark:bg-primary-400/10 dark:text-primary-200 dark:ring-primary-400/20">
                                    <x-heroicon-o-cpu-chip class="h-5 w-5" />
                                </div>
                                <div class="w-full max-w-[42rem] rounded-2xl bg-white/85 px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-black/5 dark:bg-gray-900/60 dark:text-gray-100 dark:ring-white/10">
                                    <div class="mb-2 text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Da approvare</div>

                                    <div class="space-y-3">
                                        @foreach($pendingCommunicationProposals as $proposal)
                                            <div wire:key="communication-proposal-{{ $proposal['public_id'] }}" class="rounded-xl border border-amber-200 bg-amber-50/80 p-3 dark:border-amber-900/40 dark:bg-amber-950/30">
                                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                                    <div class="min-w-0">
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold uppercase text-amber-900 dark:bg-amber-300/20 dark:text-amber-100">{{ $proposal['source'] }}</span>
                                                            <span class="text-[11px] text-amber-800 dark:text-amber-100/70">{{ $proposal['created_at'] }}</span>
                                                        </div>
                                                        <div class="mt-1 font-medium text-gray-950 dark:text-white">{{ $proposal['action_label'] }}</div>
                                                        <div class="mt-1 text-xs text-gray-600 dark:text-gray-300">{{ $proposal['preview'] }}</div>
                                                    </div>
                                                    <div class="flex shrink-0 gap-2">
                                                        <button type="button" wire:click="approveCommunicationProposal('{{ $proposal['public_id'] }}')" class="rounded-lg bg-primary-600 px-3 py-2 text-xs font-semibold text-white">
                                                            Approva
                                                        </button>
                                                        <button type="button" wire:click="cancelCommunicationProposal('{{ $proposal['public_id'] }}')" class="rounded-lg border border-amber-300 px-3 py-2 text-xs font-semibold text-amber-950 dark:border-amber-500/40 dark:text-amber-100">
                                                            Scarta
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2">
                                                    @if($proposal['requires_work_order'])
                                                        <label class="block">
                                                            <span class="sr-only">Commessa</span>
                                                            <input
                                                                type="search"
                                                                placeholder="Cerca commessa..."
                                                                wire:model.live.debounce.350ms="proposalWorkOrderSearches.{{ $proposal['public_id'] }}"
                                                                class="mb-2 block w-full rounded-lg border-amber-200 bg-white px-3 py-2 text-xs text-gray-950 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-amber-500/30 dark:bg-gray-950 dark:text-gray-100"
                                                            />
                                                            <select
                                                                wire:model.defer="proposalWorkOrderIds.{{ $proposal['public_id'] }}"
                                                                class="block w-full rounded-lg border-amber-200 bg-white px-3 py-2 text-xs text-gray-950 shadow-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-amber-500/30 dark:bg-gray-950 dark:text-gray-100"
                                                            >
                                                                <option value="">Commessa...</option>
                                                                @foreach($proposal['work_order_options'] as $workOrderId => $workOrderLabel)
                                                                    <option value="{{ $workOrderId }}">{{ $workOrderLabel }}</option>
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                    @endif

                                                    @if($proposal['requires_intervention'])
                                                        <label class="block">
                                                            <span class="sr-only">Intervento</span>
                                                            <select
                                                                wire:model.defer="proposalInterventionIds.{{ $proposal['public_id'] }}"
                                                                class="block w-full rounded-lg border-amber-200 bg-white px-3 py-2 text-xs text-gray-950 shadow-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-amber-500/30 dark:bg-gray-950 dark:text-gray-100"
                                                            >
                                                                <option value="">Intervento...</option>
                                                                @foreach($proposal['intervention_options'] as $interventionId => $interventionLabel)
                                                                    <option value="{{ $interventionId }}">{{ $interventionLabel }}</option>
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                    @endif

                                                    @if($proposal['uses_schedule'])
                                                        <label class="block">
                                                            <span class="sr-only">Data e ora</span>
                                                            <input
                                                                type="datetime-local"
                                                                wire:model.defer="proposalScheduledAts.{{ $proposal['public_id'] }}"
                                                                class="block w-full rounded-lg border-amber-200 bg-white px-3 py-2 text-xs text-gray-950 shadow-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-amber-500/30 dark:bg-gray-950 dark:text-gray-100"
                                                            />
                                                        </label>
                                                    @endif

                                                    @if($proposal['supports_materials'])
                                                        <label class="block">
                                                            <span class="sr-only">Materiale extra</span>
                                                            <input
                                                                type="text"
                                                                placeholder="Materiale extra"
                                                                wire:model.defer="proposalMaterialDescriptions.{{ $proposal['public_id'] }}"
                                                                class="block w-full rounded-lg border-amber-200 bg-white px-3 py-2 text-xs text-gray-950 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-amber-500/30 dark:bg-gray-950 dark:text-gray-100"
                                                            />
                                                        </label>

                                                        <label class="block">
                                                            <span class="sr-only">Costo materiali</span>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                step="0.01"
                                                                placeholder="Costo materiali €"
                                                                wire:model.defer="proposalMaterialCosts.{{ $proposal['public_id'] }}"
                                                                class="block w-full rounded-lg border-amber-200 bg-white px-3 py-2 text-xs text-gray-950 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-amber-500/30 dark:bg-gray-950 dark:text-gray-100"
                                                            />
                                                        </label>
                                                    @endif

                                                    @if($proposal['supports_labor'])
                                                        <label class="block">
                                                            <span class="sr-only">Manodopera</span>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                step="0.01"
                                                                placeholder="Manodopera €"
                                                                wire:model.defer="proposalLaborAmounts.{{ $proposal['public_id'] }}"
                                                                class="block w-full rounded-lg border-amber-200 bg-white px-3 py-2 text-xs text-gray-950 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-amber-500/30 dark:bg-gray-950 dark:text-gray-100"
                                                            />
                                                        </label>
                                                    @endif

                                                    <label class="block sm:col-span-2">
                                                        <span class="sr-only">Testo</span>
                                                        <textarea
                                                            wire:model.defer="proposalBodies.{{ $proposal['public_id'] }}"
                                                            rows="2"
                                                            class="block w-full rounded-lg border-amber-200 bg-white px-3 py-2 text-xs text-gray-950 shadow-sm outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 dark:border-amber-500/30 dark:bg-gray-950 dark:text-gray-100"
                                                        ></textarea>
                                                    </label>
                                                </div>

                                                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs">
                                                    @if(count($proposal['work_order_options']) === 0)
                                                        <a href="{{ $proposal['work_order_create_url'] }}" target="_blank" rel="noopener" class="font-semibold text-primary-700 underline underline-offset-2 dark:text-primary-300">+ Nuova commessa</a>
                                                    @endif
                                                    <details>
                                                        <summary class="cursor-pointer font-semibold text-gray-600 dark:text-gray-300">Messaggio</summary>
                                                        <pre class="mt-2 max-h-48 overflow-auto whitespace-pre-wrap rounded-lg bg-white p-3 text-xs text-gray-800 ring-1 ring-amber-100 dark:bg-gray-950 dark:text-gray-100 dark:ring-amber-500/20">{{ $proposal['full_message'] }}</pre>
                                                    </details>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div id="ai-empty" class="rounded-2xl border border-gray-200 bg-white/70 p-4 text-sm text-gray-700 shadow-sm dark:border-gray-800 dark:bg-gray-950/60 dark:text-gray-200">
                                Scrivi una richiesta oppure usa un’azione rapida.
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-gray-200 p-3 dark:border-gray-800">
                        <div class="flex items-end gap-2">
                            <label class="sr-only" for="ai-input">Messaggio</label>
                            <textarea
                                id="ai-input"
                                rows="1"
                                class="max-h-32 min-h-[2.75rem] w-full min-w-0 flex-1 resize-none rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-800 dark:bg-gray-950 dark:text-gray-100"
                                placeholder="{{ $this->inputPlaceholder }}"
                            ></textarea>

                            <button
                                type="button"
                                id="ai-mic"
                                title="Registra"
                                aria-label="Registra"
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white shadow-sm transition hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-950 dark:hover:bg-gray-900"
                            >
                                <span class="block h-3.5 w-3.5 rounded-full bg-red-500"></span>
                            </button>
                            <button
                                type="button"
                                id="ai-send"
                                title="Invia"
                                aria-label="Invia"
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-primary-600 text-white shadow-lg shadow-black/10 transition hover:bg-primary-500 disabled:opacity-50"
                            >
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.4 4.2 20.6 12 3.4 19.8 6 12Zm0 0h7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

@push('scripts')
    <script>
        (function () {
            const messagesEl = document.getElementById('ai-messages');
            const inputEl = document.getElementById('ai-input');
            const sendBtn = document.getElementById('ai-send');
            const micBtn = document.getElementById('ai-mic');
            const clearBtn = document.getElementById('ai-clear');
            const statusEl = document.getElementById('ai-status');

            if (!messagesEl || !inputEl || !sendBtn || !micBtn || !clearBtn || !statusEl) return;

            const REC_IDLE_ICON = '<span class="block h-3.5 w-3.5 rounded-full bg-red-500"></span>';
            const REC_ACTIVE_ICON = '<span class="block h-3 w-3 rounded-[3px] bg-red-600"></span>';

            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const contextEl = document.getElementById('ai-context');

            // Optional text-to-speech reply (default OFF, persisted).
            // Uses OpenAI's natural TTS; falls back to the browser voice.
            const voiceToggle = document.getElementById('ai-voice-toggle');
            const browserTts = 'speechSynthesis' in window;
            let voiceOn = localStorage.getItem('ai-voice-reply') === '1';
            let ttsAudio = null;

            function renderVoiceToggle() {
                if (!voiceToggle) return;
                voiceToggle.setAttribute('aria-pressed', voiceOn ? 'true' : 'false');
                voiceToggle.innerHTML = voiceOn ? '🔊 Voce on' : '🔇 Voce off';
                voiceToggle.classList.toggle('border-primary-300', voiceOn);
                voiceToggle.classList.toggle('bg-primary-50', voiceOn);
                voiceToggle.classList.toggle('text-primary-700', voiceOn);
                voiceToggle.classList.toggle('text-gray-500', !voiceOn);
                voiceToggle.classList.toggle('bg-white', !voiceOn);
            }

            function stopSpeaking() {
                try { if (ttsAudio) { ttsAudio.pause(); ttsAudio = null; } } catch (_) {}
                try { if (browserTts) window.speechSynthesis.cancel(); } catch (_) {}
            }

            function speakBrowser(text) {
                if (!browserTts) return;
                try {
                    const u = new SpeechSynthesisUtterance(text);
                    u.lang = 'it-IT';
                    u.rate = 1.05;
                    window.speechSynthesis.speak(u);
                } catch (_) {}
            }

            async function speak(text) {
                if (!voiceOn || !text) return;
                stopSpeaking();
                try {
                    const res = await fetch('/aiassistant/tts', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'audio/mpeg',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ text }),
                    });
                    if (!res.ok) throw new Error('tts');
                    const blob = await res.blob();
                    ttsAudio = new Audio(URL.createObjectURL(blob));
                    ttsAudio.play().catch(() => speakBrowser(text));
                } catch (_) {
                    speakBrowser(text);
                }
            }

            voiceToggle?.addEventListener('click', () => {
                voiceOn = !voiceOn;
                localStorage.setItem('ai-voice-reply', voiceOn ? '1' : '0');
                if (!voiceOn) stopSpeaking();
                renderVoiceToggle();
            });
            renderVoiceToggle();
            const context = {
                interventionId: Number.parseInt(contextEl?.dataset?.interventionId || '', 10) || null,
                warehouseId: Number.parseInt(contextEl?.dataset?.warehouseId || '', 10) || null,
                panelId: (contextEl?.dataset?.panelId || 'operator').trim() || 'operator',
                threadPublicId: (contextEl?.dataset?.threadPublicId || '').trim(),
            };
            const initialMessagesEl = document.getElementById('ai-initial-messages');
            let initialMessages = [];
            try {
                initialMessages = JSON.parse(initialMessagesEl?.textContent || '[]');
            } catch (_) {
                initialMessages = [];
            }

            let threadPublicId = context.threadPublicId;
            let isSending = false;
            let isRecording = false;
            let mediaRecorder = null;
            let mediaChunks = [];

            function setStatus(text) {
                const t = (text || '').trim();
                statusEl.textContent = t;
                statusEl.classList.toggle('hidden', t === '');
            }

            function scrollToBottom() {
                messagesEl.scrollTop = messagesEl.scrollHeight;
            }

            function buildContextPrefix() {
                const parts = [];
                if (context.panelId) parts.push(`panel_id=${context.panelId}`);
                if (context.interventionId) parts.push(`intervention_id=${context.interventionId}`);
                if (context.warehouseId) parts.push(`warehouse_id=${context.warehouseId}`);
                return parts.length ? `[contesto ${parts.join(' ')}] ` : '';
            }

            function escapeHtml(str) {
                return (str || '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function formatInlineMarkdown(str) {
                return escapeHtml(str || '').replace(/\*\*(.+?)\*\*/gs, '<strong>$1</strong>');
            }

            function splitMarkdownTableRow(line) {
                const trimmed = (line || '').trim().replace(/^\|/, '').replace(/\|$/, '');

                return trimmed.split('|').map(cell => cell.trim());
            }

            function isMarkdownTableDivider(line) {
                const cells = splitMarkdownTableRow(line);

                return cells.length > 1 && cells.every(cell => /^:?-{3,}:?$/.test(cell));
            }

            function renderMarkdownTable(lines) {
                const headers = splitMarkdownTableRow(lines[0] || '');
                const rows = lines.slice(2).map(splitMarkdownTableRow);
                const columnCount = headers.length;
                const normalizedRows = rows.filter(row => row.length === columnCount);

                if (columnCount < 2 || normalizedRows.length === 0) {
                    return '';
                }

                const headerHtml = headers
                    .map(header => `<th class="whitespace-nowrap px-3 py-2 text-left font-semibold">${formatInlineMarkdown(header)}</th>`)
                    .join('');
                const rowsHtml = normalizedRows
                    .map(row => `
                        <tr class="border-t border-gray-200 dark:border-gray-700">
                            ${row.map(cell => `<td class="px-3 py-2 align-top">${formatInlineMarkdown(cell)}</td>`).join('')}
                        </tr>
                    `)
                    .join('');

                return `
                    <div class="my-2 overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs dark:divide-gray-700">
                            <thead class="bg-gray-50 text-gray-700 dark:bg-gray-800/70 dark:text-gray-200">
                                <tr>${headerHtml}</tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white/70 text-gray-800 dark:divide-gray-800 dark:bg-gray-900/40 dark:text-gray-100">
                                ${rowsHtml}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            function formatMessageHtml(str) {
                const lines = (str || '').split(/\r?\n/);
                const blocks = [];
                let paragraph = [];

                const flushParagraph = () => {
                    const text = paragraph.join('\n').trim();
                    paragraph = [];
                    if (text !== '') {
                        blocks.push(`<p>${formatInlineMarkdown(text).replace(/\n/g, '<br>')}</p>`);
                    }
                };

                for (let i = 0; i < lines.length; i += 1) {
                    if (i + 1 < lines.length && lines[i].includes('|') && isMarkdownTableDivider(lines[i + 1])) {
                        flushParagraph();

                        const tableLines = [lines[i], lines[i + 1]];
                        i += 2;

                        while (i < lines.length && lines[i].includes('|') && splitMarkdownTableRow(lines[i]).length === splitMarkdownTableRow(tableLines[0]).length) {
                            tableLines.push(lines[i]);
                            i += 1;
                        }

                        i -= 1;
                        blocks.push(renderMarkdownTable(tableLines));
                        continue;
                    }

                    if (lines[i].trim() === '') {
                        flushParagraph();
                        continue;
                    }

                    paragraph.push(lines[i]);
                }

                flushParagraph();

                return blocks.filter(Boolean).length > 0 ? blocks.filter(Boolean).join('') : '<p></p>';
            }

            function assistantAvatarHtml() {
                return `
                    <div class="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-600/10 text-primary-700 ring-1 ring-primary-600/20 dark:bg-primary-400/10 dark:text-primary-200 dark:ring-primary-400/20">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 2.25h6M12 2.25V6m-6.75 4.5h13.5A2.25 2.25 0 0 1 21 12.75v3A4.5 4.5 0 0 1 16.5 20.25h-9A4.5 4.5 0 0 1 3 15.75v-3A2.25 2.25 0 0 1 5.25 10.5Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 14.25h.01M15.75 14.25h.01"/>
                        </svg>
                    </div>
                `;
            }

            function getInlineProposalsEl() {
                let el = document.getElementById('ai-inline-proposals');
                if (!el) {
                    el = document.createElement('div');
                    el.id = 'ai-inline-proposals';
                }

                el.className = 'pt-1';
                messagesEl.appendChild(el);

                return el;
            }

            function addBubble(role, text) {
                const emptyEl = document.getElementById('ai-empty');
                if (emptyEl) emptyEl.remove();

                const wrapper = document.createElement('div');
                const isUser = role === 'user';
                wrapper.className = 'flex items-start gap-2 ' + (isUser ? 'justify-end' : 'justify-start');
                const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                wrapper.innerHTML = `
                    ${isUser ? '' : `
                        ${assistantAvatarHtml()}
                    `}
                    <div class="group ${isUser ? 'bg-primary-600 text-white' : 'bg-white/85 text-gray-900 dark:bg-gray-900/60 dark:text-gray-100'} w-fit max-w-[42rem] rounded-2xl px-3 py-2 text-sm leading-5 shadow-sm ring-1 ring-black/5 dark:ring-white/10">
                        <div data-ai-content class="space-y-2 break-words [&_p]:m-0 [&_strong]:font-semibold">${isUser ? formatMessageHtml(text) : formatMessageHtml(text)}</div>
                        <div class="mt-2 flex items-center justify-end gap-2 text-[11px] ${isUser ? 'text-white/70' : 'text-gray-400 dark:text-gray-500'} opacity-0 transition group-hover:opacity-100">
                            <span>${escapeHtml(time)}</span>
                        </div>
                    </div>
                    ${isUser ? `
                        <div class="h-9 w-9 shrink-0 rounded-full bg-primary-700 ring-1 ring-white/10 dark:ring-white/15"></div>
                    ` : ''}
                `;
                messagesEl.appendChild(wrapper);
                scrollToBottom();
                return wrapper.querySelector('[data-ai-content]');
            }

            function renderInitialMessages() {
                if (!Array.isArray(initialMessages) || initialMessages.length === 0) return;

                initialMessages.forEach((message) => {
                    const role = message?.role === 'user' ? 'user' : 'assistant';
                    addBubble(role, message?.content || '');
                });
            }

            function setProposals(proposals) {
                if (!proposals || proposals.length === 0) {
                    document.getElementById('ai-inline-proposals')?.remove();
                    return;
                }

                const container = getInlineProposalsEl();
                const cards = proposals.map((p) => {
                    const preview = p.preview || p.type;

                    return `
                        <div class="rounded-2xl border border-amber-200 bg-amber-50/90 p-3 shadow-sm dark:border-amber-900/40 dark:bg-amber-950/30">
                            <div class="text-[11px] font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-300">${escapeHtml(p.type_label || p.type || 'Azione proposta')}</div>
                            <div class="mt-1 text-sm text-gray-900 dark:text-gray-100">${escapeHtml(preview)}</div>
                            <div class="mt-3 flex gap-2">
                                <button type="button" data-proposal-id="${escapeHtml(p.public_id)}" class="ai-confirm inline-flex items-center justify-center rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-500">
                                    Conferma
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');

                container.innerHTML = `
                    <div class="flex items-start gap-2">
                        ${assistantAvatarHtml()}
                        <div class="w-full max-w-[42rem] rounded-2xl bg-white/85 px-3 py-2 text-sm text-gray-900 shadow-sm ring-1 ring-black/5 dark:bg-gray-900/60 dark:text-gray-100 dark:ring-white/10">
                            <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Azioni proposte</div>
                            <div class="mt-1 text-xs text-gray-600 dark:text-gray-300">Le richieste che modificano dati vengono eseguite solo dopo conferma.</div>
                            <div class="mt-3 space-y-3">${cards}</div>
                        </div>
                    </div>
                `;

                container.querySelectorAll('.ai-confirm').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const proposalId = btn.getAttribute('data-proposal-id');
                        if (!proposalId) return;
                        await confirmProposal(proposalId);
                    });
                });
            }

            async function fetchProposals() {
                if (!threadPublicId) return;
                try {
                    const res = await fetch(`/aiassistant/threads/${encodeURIComponent(threadPublicId)}/proposals`, {
                        credentials: 'same-origin',
                    });
                    const data = await res.json();
                    if (data && data.ok) {
                        setProposals(data.proposals || []);
                    }
                } catch (e) {
                    // ignore
                }
            }

            async function confirmProposal(proposalPublicId) {
                try {
                    const res = await fetch(`/aiassistant/actions/${encodeURIComponent(proposalPublicId)}/confirm`, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json',
                        },
                    });
                    const data = await res.json();
                    if (data?.ok) {
                        addBubble('assistant', 'Azione eseguita.');
                    } else {
                        addBubble('assistant', `Non riesco ad eseguire: ${data?.result?.error || data?.error || 'errore'}`);
                    }
                    await fetchProposals();
                } catch (e) {
                    addBubble('assistant', 'Errore durante la conferma azione.');
                }
            }

            async function transcribeAudio(blob) {
                const form = new FormData();
                form.append('audio', blob, 'audio.webm');
                form.append('lang', 'it-IT');

                const res = await fetch('/aiassistant/stt', {
                    method: 'POST',
                    body: form,
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                });
                return await res.json();
            }

            async function sendMessage() {
                const text = (inputEl.value || '').trim();
                if (!text || isSending) return;
                const effectiveText = `${buildContextPrefix()}${text}`.trim();

                stopSpeaking();

                isSending = true;
                sendBtn.disabled = true;
                micBtn.disabled = true;
                inputEl.disabled = true;

                addBubble('user', text);
                inputEl.value = '';
                setStatus('Sto pensando…');

                let assistantBubble = addBubble('assistant', '');
                let assistantText = '';

                const url = new URL('/aiassistant/chat/stream', window.location.origin);
                url.searchParams.set('message', effectiveText);
                if (threadPublicId) url.searchParams.set('thread', threadPublicId);
                if (context.panelId) url.searchParams.set('panel', context.panelId);
                if (context.interventionId) url.searchParams.set('context_intervention_id', String(context.interventionId));
                if (context.warehouseId) url.searchParams.set('context_warehouse_id', String(context.warehouseId));

                const es = new EventSource(url.toString(), { withCredentials: true });

                es.addEventListener('thread', (e) => {
                    try {
                        const payload = JSON.parse(e.data);
                        if (payload.thread) threadPublicId = payload.thread;
                    } catch (_) {}
                });

                es.addEventListener('token', (e) => {
                    try {
                        const payload = JSON.parse(e.data);
                        if (payload.token) {
                            assistantText += payload.token;
                            assistantBubble.innerHTML = formatMessageHtml(assistantText);
                            scrollToBottom();
                        }
                    } catch (_) {}
                });

                es.addEventListener('spoken', (e) => {
                    try {
                        const payload = JSON.parse(e.data);
                        if (payload.text) speak(payload.text);
                    } catch (_) {}
                });

                es.addEventListener('error', () => {
                    es.close();
                    setStatus('Errore durante lo streaming.');

                    isSending = false;
                    sendBtn.disabled = false;
                    micBtn.disabled = false;
                    inputEl.disabled = false;
                });

                es.addEventListener('done', async () => {
                    es.close();
                    setStatus('');

                    isSending = false;
                    sendBtn.disabled = false;
                    micBtn.disabled = false;
                    inputEl.disabled = false;
                    inputEl.focus();

                    await fetchProposals();
                });
            }

            async function toggleRecording() {
                if (isRecording) {
                    try {
                        mediaRecorder?.stop();
                    } catch (_) {}
                    return;
                }

                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    const options = {};
                    mediaChunks = [];
                    mediaRecorder = new MediaRecorder(stream, options);
                    mediaRecorder.addEventListener('dataavailable', (e) => {
                        if (e.data && e.data.size > 0) {
                            mediaChunks.push(e.data);
                        }
                    });
                    mediaRecorder.addEventListener('stop', async () => {
                        isRecording = false;
                        micBtn.innerHTML = REC_IDLE_ICON;
                        micBtn.classList.remove('animate-pulse', 'ring-2', 'ring-red-400');
                        setStatus('Trascrivo audio…');

                        const blob = new Blob(mediaChunks, { type: mediaChunks[0]?.type || 'audio/webm' });
                        try {
                            const data = await transcribeAudio(blob);
                            if (data?.ok && data.text) {
                                const previous = (inputEl.value || '').trim();
                                if (previous === '') {
                                    inputEl.value = data.text;
                                    await sendMessage();
                                } else {
                                    inputEl.value = (inputEl.value ? (inputEl.value + '\n') : '') + data.text;
                                }
                            } else {
                                addBubble('assistant', `Trascrizione fallita: ${data?.error || 'errore'}`);
                            }
                        } catch (e) {
                            addBubble('assistant', 'Trascrizione fallita.');
                        } finally {
                            setStatus('');
                        }

                        try { stream.getTracks().forEach(t => t.stop()); } catch (_) {}
                    });

                    isRecording = true;
                    micBtn.innerHTML = REC_ACTIVE_ICON;
                    micBtn.classList.add('animate-pulse', 'ring-2', 'ring-red-400');
                    setStatus('Registrazione…');
                    mediaRecorder.start();
                } catch (e) {
                    addBubble('assistant', 'Permesso microfono negato o non disponibile.');
                }
            }

            clearBtn.addEventListener('click', () => {
                messagesEl.innerHTML = '';
                threadPublicId = '';
                setProposals([]);
                const empty = document.createElement('div');
                empty.id = 'ai-empty';
                empty.className = 'rounded-2xl border border-gray-200 bg-white/70 p-4 text-sm text-gray-700 shadow-sm dark:border-gray-800 dark:bg-gray-950/60 dark:text-gray-200';
                empty.textContent = 'Scrivi una richiesta oppure usa un’azione rapida.';
                messagesEl.appendChild(empty);
                scrollToBottom();
            });

            sendBtn.addEventListener('click', sendMessage);
            micBtn.addEventListener('click', toggleRecording);
            renderInitialMessages();

            const helpBtn = document.getElementById('ai-help');
            const helpPanel = document.getElementById('ai-help-panel');
            if (helpBtn && helpPanel) {
                helpBtn.addEventListener('click', () => {
                    helpPanel.classList.toggle('hidden');
                });
            }

            document.querySelectorAll('[data-ai-prompt]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const prompt = btn.getAttribute('data-ai-prompt') || '';
                    if (!prompt) return;
                    inputEl.value = (inputEl.value ? (inputEl.value + '\n') : '') + prompt;
                    inputEl.focus();
                });
            });

            const exampleSelect = document.getElementById('ai-example-select');
            if (exampleSelect) {
                exampleSelect.addEventListener('change', () => {
                    const prompt = exampleSelect.value || '';
                    if (!prompt) return;
                    inputEl.value = (inputEl.value ? (inputEl.value + '\n') : '') + prompt;
                    inputEl.focus();
                    exampleSelect.value = '';
                });
            }

            inputEl.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            fetchProposals();
        })();
    </script>
@endpush

