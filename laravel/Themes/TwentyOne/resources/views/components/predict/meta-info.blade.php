{{-- Componente riutilizzabile per le meta informazioni dei predict --}}
@props([
    'predict',
    'showAuthor' => true,
    'showDate' => true,
    'showExpiration' => true
])

{{-- Informazioni aggiuntive --}}
@if($showAuthor || $showDate)
    <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-2">
        @if($showAuthor)
            <div class="flex items-center">
                @if($predict->author)
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $predict->author->name ?? __('predict::common.anonymous') }}
                @endif
            </div>
        @endif

        @if($showDate)
            <div class="flex items-center">
                @if($predict->published_at)
                    {{ \Carbon\Carbon::parse($predict->published_at)->format('d/m/Y') }}
                @else
                    {{ \Carbon\Carbon::parse($predict->created_at)->format('d/m/Y') }}
                @endif
            </div>
        @endif
    </div>
@endif

{{-- Data di scadenza --}}
@if($showExpiration)
    <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
        </svg>
        {{ $predict->getExpirationDate() }}
    </div>
@endif 