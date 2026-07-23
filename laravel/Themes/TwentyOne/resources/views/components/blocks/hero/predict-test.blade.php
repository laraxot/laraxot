@props(['article' => $record])
<div class="w-full space-y-5 lg:w-2/3">
    <div class="mb-3 border border-gray-200 pb-4">
        <h3
            class="text-sm p-2 pb-1 font-bold text-gray-900 dark:text-white mb-1 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
            {{ $article->title }}
        </h3>

        <div class="flex items-center text-xs text-gray-600 dark:text-gray-400 mb-1 pl-2">
            {{ $article->getExpirationDate() }}
        </div>

        <div class="flex items-center justify-between mt-2 px-2 pt-1">
            {{-- Numero di giocatori --}}
            <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                <svg class="w-3 h-3 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z">
                    </path>
                </svg>
                <span class="font-medium">{{ number_format($article->count_credit) }}</span>
                <span class="ml-1">{{ __('predict::common.bets_count.label') }}</span>
            </div>

            {{-- Crediti totali giocati --}}
            <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                <svg class="w-3 h-3 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ number_format($article->sum_credit) }}</span>
                <span class="ml-1">{{ __('predict::common.credits.label') }}</span>
            </div>
        </div>
    </div>

    @if (is_array($article->content_blocks))
        <x-render.blocks :blocks="$article->content_blocks" :model="$article" />
    @endif
</div>
