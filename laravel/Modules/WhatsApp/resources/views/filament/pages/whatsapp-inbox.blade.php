<x-filament-panels::page>
    @php($conversations = $this->conversationList())
    @php($messages = $this->thread())

    <div class="grid gap-4 md:grid-cols-[320px_1fr]">
        {{-- Conversation list --}}
        <div class="flex flex-col gap-2 md:max-h-[70vh] md:overflow-y-auto">
            @forelse ($conversations as $conversation)
                <button
                    type="button"
                    wire:click="selectConversation({{ $conversation['id'] }})"
                    @class([
                        'w-full rounded-xl border p-3 text-left transition hover:bg-gray-50 dark:hover:bg-white/5',
                        'border-primary-400 bg-primary-50 dark:border-primary-500/40 dark:bg-primary-500/10' => $selectedConversationId === $conversation['id'],
                        'border-gray-200 dark:border-white/10' => $selectedConversationId !== $conversation['id'],
                    ])
                >
                    <div class="flex items-center justify-between gap-2">
                        <span class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $conversation['name'] }}</span>
                        <span class="shrink-0 text-[10px] text-gray-400">{{ $conversation['last_at'] }}</span>
                    </div>
                    <div class="mt-0.5 flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                        @if ($conversation['last_direction'] === 'outbound')
                            <span class="text-primary-500">↩</span>
                        @endif
                        <span class="truncate">{{ $conversation['last_message'] ?: '—' }}</span>
                    </div>
                    <div class="mt-1 flex items-center gap-2">
                        @unless ($conversation['is_linked'])
                            <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-800 dark:bg-amber-500/20 dark:text-amber-200">Da collegare</span>
                        @endunless
                        <span class="text-[10px] text-gray-400">{{ $conversation['messages_count'] }} messaggi</span>
                    </div>
                </button>
            @empty
                <p class="rounded-xl border border-dashed border-gray-200 p-4 text-sm text-gray-500 dark:border-white/10">Nessuna conversazione WhatsApp.</p>
            @endforelse
        </div>

        {{-- Thread --}}
        <div class="rounded-xl border border-gray-200 p-4 dark:border-white/10 md:max-h-[70vh] md:overflow-y-auto">
            @if ($selectedConversationId === null)
                <p class="flex h-full items-center justify-center text-sm text-gray-400">Seleziona una conversazione per leggere i messaggi.</p>
            @elseif (count($messages) === 0)
                <p class="text-sm text-gray-400">Nessun messaggio in questa conversazione.</p>
            @else
                <div class="flex flex-col gap-2">
                    @foreach ($messages as $message)
                        <div @class([
                            'max-w-[80%] rounded-2xl px-3 py-2 text-sm',
                            'ml-auto bg-primary-600 text-white' => $message['direction'] === 'outbound',
                            'mr-auto bg-gray-100 text-gray-900 dark:bg-white/10 dark:text-gray-100' => $message['direction'] !== 'outbound',
                        ])>
                            <div class="whitespace-pre-line">{{ $message['body'] ?: '—' }}</div>
                            <div @class([
                                'mt-1 text-[10px]',
                                'text-white/70' => $message['direction'] === 'outbound',
                                'text-gray-400' => $message['direction'] !== 'outbound',
                            ])>{{ $message['at'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
