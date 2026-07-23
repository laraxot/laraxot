<x-filament-panels::page>
    <div class="mx-auto w-full max-w-3xl space-y-3">
        {{-- Filters --}}
        <div class="flex flex-wrap items-center gap-2 text-sm">
            <select wire:model.live="channel" class="rounded-lg border-gray-200 py-1.5 text-sm dark:border-white/10 dark:bg-white/5">
                <option value="all">Tutti i canali</option>
                <option value="whatsapp">WhatsApp</option>
                <option value="email">Email</option>
            </select>
            <select wire:model.live="status" class="rounded-lg border-gray-200 py-1.5 text-sm dark:border-white/10 dark:bg-white/5">
                <option value="all">Tutti gli stati</option>
                <option value="needs_review">Da approvare</option>
                <option value="pending">In coda</option>
                <option value="processing">In elaborazione</option>
                <option value="processed">Elaborato</option>
                <option value="failed">Errore</option>
            </select>
        </div>

        {{-- Messages --}}
        <div class="space-y-3">
            @forelse ($this->messages() as $message)
                <div wire:key="{{ $message['channel'] }}-{{ $message['id'] }}" class="rounded-2xl border border-gray-200/80 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                    {{-- Header --}}
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex min-w-0 items-center gap-2.5">
                            <span @class([
                                'flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-white shadow-sm bg-gradient-to-br',
                                'from-green-400 to-emerald-600' => $message['channel'] === 'whatsapp',
                                'from-blue-400 to-indigo-600' => $message['channel'] !== 'whatsapp',
                            ])>
                                @if ($message['channel'] === 'whatsapp')
                                    <x-heroicon-o-chat-bubble-left-right class="h-4 w-4" />
                                @else
                                    <x-heroicon-o-envelope class="h-4 w-4" />
                                @endif
                            </span>
                            <div class="min-w-0">
                                <div class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $message['contact'] ?: $message['title'] }}</div>
                                <div class="truncate text-xs text-gray-400">
                                    @if (($message['contact_sub'] ?? '') !== ''){{ $message['contact_sub'] }} · @endif{{ $message['received_at'] ?: '—' }}
                                </div>
                            </div>
                        </div>
                        @if ($message['status'] !== 'processed' || $message['proposal_status'] === 'pending')
                            <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $message['status_class'] }}">{{ $message['status_label'] }}</span>
                        @endif
                    </div>

                    {{-- Message body --}}
                    @if (count($message['thread'] ?? []) > 0)
                        <div x-data="{ open: false }" class="mt-2">
                            <button type="button" x-on:click="open = !open" class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400">
                                <x-heroicon-m-chat-bubble-left-ellipsis class="h-3.5 w-3.5" />
                                <span x-text="open ? 'Nascondi conversazione' : 'Mostra conversazione ({{ count($message['thread']) }})'"></span>
                            </button>
                            <div x-show="open" x-collapse x-cloak class="mt-2 flex flex-col gap-1.5">
                                @foreach ($message['thread'] as $entry)
                                    <div @class([
                                        'max-w-[85%] rounded-2xl px-3 py-1.5 text-sm',
                                        'ml-auto bg-primary-600 text-white' => $entry['from'] === 'noi',
                                        'mr-auto bg-gray-100 text-gray-800 dark:bg-white/10 dark:text-gray-100' => $entry['from'] !== 'noi',
                                    ])>
                                        <span class="whitespace-pre-line">{{ $entry['text'] }}</span>
                                        <span @class(['ml-1 text-[10px]', 'text-white/60' => $entry['from'] === 'noi', 'text-gray-400' => $entry['from'] !== 'noi'])>{{ $entry['at'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif ($message['body'] !== '')
                        <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-gray-700 dark:text-gray-200">{{ $message['body'] }}</p>
                    @endif

                    {{-- Error --}}
                    @if ($message['error'] !== '')
                        <div class="mt-3 rounded-lg bg-rose-50 px-3 py-2 text-xs text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">{{ $message['error'] }}</div>
                    @endif

                    {{-- Linked result --}}
                    @if ($message['work_order_id'] || $message['intervention_id'])
                        <div class="mt-3 flex flex-wrap gap-2 text-xs text-gray-500">
                            @if ($message['work_order_id'])<span class="rounded-full bg-gray-100 px-2 py-0.5 dark:bg-white/10">Commessa #{{ $message['work_order_id'] }}</span>@endif
                            @if ($message['intervention_id'])<span class="rounded-full bg-gray-100 px-2 py-0.5 dark:bg-white/10">Intervento #{{ $message['intervention_id'] }}</span>@endif
                        </div>
                    @endif

                    {{-- AI proposal --}}
                    @if ($message['proposal_public_id'])
                        <div class="mt-3 overflow-hidden rounded-2xl bg-gradient-to-br from-primary-50 to-white p-3.5 ring-1 ring-primary-100 dark:from-primary-500/10 dark:to-transparent dark:ring-primary-500/20">
                            <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-primary-600 dark:text-primary-300">
                                <x-heroicon-s-sparkles class="h-3.5 w-3.5" />
                                Proposta AI
                            </div>
                            <div class="mt-1 text-[15px] font-semibold leading-snug text-gray-900 dark:text-gray-100">{{ $message['proposal_creates_summary'] ?: ($message['proposal_action_label'] ?: 'Azione proposta') }}</div>
                            @if ($message['proposal_is_organize'] && $message['proposal_preview'])
                                <div class="mt-2 whitespace-pre-line rounded-xl bg-white/70 px-3 py-2 text-xs leading-relaxed text-gray-600 ring-1 ring-black/5 dark:bg-white/5 dark:text-gray-300 dark:ring-white/5">{{ $message['proposal_preview'] }}</div>
                            @endif

                            @if ($message['proposal_status'] === 'pending')
                                <div class="mt-3 flex items-center gap-2">
                                    <button type="button" wire:click="approveProposal('{{ $message['proposal_public_id'] }}')" wire:loading.attr="disabled" class="flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-500 active:scale-[0.98] disabled:opacity-60">
                                        <x-heroicon-o-check-circle class="h-4 w-4" />
                                        Approva
                                    </button>
                                    <button type="button" wire:click="cancelProposal('{{ $message['proposal_public_id'] }}')" class="rounded-xl px-3 py-2.5 text-sm font-medium text-gray-500 transition hover:bg-white/70 dark:text-gray-400 dark:hover:bg-white/10">Scarta</button>
                                </div>
                            @else
                                <div class="mt-2 text-xs font-medium text-gray-400">{{ $message['proposal_status'] }}</div>
                            @endif

                            {{-- Collapsible review --}}
                            @if ($message['proposal_status'] === 'pending' && ($message['proposal_is_organize'] || $message['proposal_requires_work_order'] || $message['proposal_requires_intervention'] || $message['proposal_uses_schedule'] || $message['proposal_supports_materials'] || $message['proposal_supports_labor']))
                                <div x-data="{ open: false }" class="mt-2">
                                    <button type="button" x-on:click="open = !open" class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-500 dark:text-primary-300">
                                        <span x-text="open ? 'Nascondi' : 'Modifica prima di approvare'"></span>
                                        <x-heroicon-m-chevron-down class="h-3.5 w-3.5 transition-transform" x-bind:class="open ? 'rotate-180' : ''" />
                                    </button>

                                    <div x-show="open" x-collapse x-cloak class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        @if ($message['proposal_is_organize'])
                                            <label class="block sm:col-span-2">
                                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Nome cliente</span>
                                                <input type="text" wire:model.defer="proposalCustomerNames.{{ $message['proposal_public_id'] }}" class="block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                            </label>
                                            <label class="block sm:col-span-2">
                                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Indirizzo</span>
                                                <input type="text" wire:model.defer="proposalAddresses.{{ $message['proposal_public_id'] }}" class="block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                            </label>
                                            <label class="block sm:col-span-2">
                                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Lavoro da fare</span>
                                                <textarea wire:model.defer="proposalProblems.{{ $message['proposal_public_id'] }}" rows="2" class="block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5"></textarea>
                                            </label>
                                            <label class="block sm:col-span-2">
                                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Appuntamento (opzionale)</span>
                                                <input type="datetime-local" wire:model.defer="proposalScheduledAts.{{ $message['proposal_public_id'] }}" class="block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                            </label>
                                        @endif

                                        @if ($message['proposal_requires_work_order'])
                                            <label class="block sm:col-span-2">
                                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Commessa</span>
                                                <input type="search" placeholder="Cerca cliente, titolo o codice…" wire:model.live.debounce.350ms="proposalWorkOrderSearches.{{ $message['proposal_public_id'] }}" class="mb-1.5 block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                                <select wire:model.defer="proposalWorkOrderIds.{{ $message['proposal_public_id'] }}" class="block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5">
                                                    <option value="">Scegli commessa…</option>
                                                    @foreach($message['work_order_options'] as $workOrderId => $workOrderLabel)
                                                        <option value="{{ $workOrderId }}">{{ $workOrderLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        @endif

                                        @if ($message['proposal_requires_intervention'])
                                            <label class="block">
                                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Intervento</span>
                                                <select wire:model.defer="proposalInterventionIds.{{ $message['proposal_public_id'] }}" class="block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5">
                                                    <option value="">Scegli intervento…</option>
                                                    @foreach($message['intervention_options'] as $interventionId => $interventionLabel)
                                                        <option value="{{ $interventionId }}">{{ $interventionLabel }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        @endif

                                        @if ($message['proposal_uses_schedule'])
                                            <label class="block">
                                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Data e ora</span>
                                                <input type="datetime-local" wire:model.defer="proposalScheduledAts.{{ $message['proposal_public_id'] }}" class="block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                            </label>
                                        @endif

                                        @if ($message['proposal_supports_materials'])
                                            <label class="block">
                                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Materiale extra</span>
                                                <input type="text" wire:model.defer="proposalMaterialDescriptions.{{ $message['proposal_public_id'] }}" class="block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                            </label>
                                            <label class="block">
                                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Costo materiali €</span>
                                                <input type="number" min="0" step="0.01" wire:model.defer="proposalMaterialCosts.{{ $message['proposal_public_id'] }}" class="block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                            </label>
                                        @endif

                                        @if ($message['proposal_supports_labor'])
                                            <label class="block">
                                                <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Manodopera €</span>
                                                <input type="number" min="0" step="0.01" wire:model.defer="proposalLaborAmounts.{{ $message['proposal_public_id'] }}" class="block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5" />
                                            </label>
                                        @endif

                                        <label class="block sm:col-span-2">
                                            <span class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Testo / nota per il gestionale</span>
                                            <textarea wire:model.defer="proposalBodies.{{ $message['proposal_public_id'] }}" rows="2" class="block w-full rounded-lg border-gray-200 text-sm dark:border-white/10 dark:bg-white/5"></textarea>
                                        </label>

                                        <div class="flex flex-wrap gap-2 sm:col-span-2">
                                            <a href="{{ $message['work_order_index_url'] }}" target="_blank" rel="noopener" class="text-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400">Apri commesse ↗</a>
                                            <a href="{{ $message['work_order_create_url'] }}" target="_blank" rel="noopener" class="text-xs font-medium text-primary-600 hover:text-primary-500 dark:text-primary-300">+ Nuova commessa ↗</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Technical details --}}
                    @if ($message['ai_result'] !== [] || $message['proposal_payload'] !== [])
                        <details class="mt-3 text-xs">
                            <summary class="cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">Dettagli tecnici</summary>
                            <div class="mt-2 grid grid-cols-1 gap-2 lg:grid-cols-2">
                                @if ($message['ai_result'] !== [])
                                    <pre class="max-h-64 overflow-auto rounded-lg bg-gray-950 p-2.5 text-[11px] text-gray-100">{{ json_encode($message['ai_result'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                                @if ($message['proposal_payload'] !== [])
                                    <pre class="max-h-64 overflow-auto rounded-lg bg-gray-950 p-2.5 text-[11px] text-gray-100">{{ json_encode($message['proposal_payload'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                            </div>
                        </details>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-200 p-8 text-center text-sm text-gray-400 dark:border-white/10">
                    Nessun messaggio con questi filtri.
                </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
