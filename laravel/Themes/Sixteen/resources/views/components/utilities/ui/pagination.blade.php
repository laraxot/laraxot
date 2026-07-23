{{-- Bootstrap Italia Pagination Component --}}
{{-- 
    Componente paginazione conforme alle specifiche Bootstrap Italia
    Supporta accessibilità WCAG 2.1 AA e navigazione da tastiera
--}}
@props([
    'paginator' => null,
    'size' => 'md', // 'sm', 'md', 'lg'
    'showInfo' => true,
    'showJumper' => false,
    'maxLinks' => 7,
    'previousText' => 'Precedente',
    'nextText' => 'Successivo',
    'firstText' => 'Primo',
    'lastText' => 'Ultimo',
])

@php
if (!$paginator) {
    return;
}

$currentPage = $paginator->currentPage();
$lastPage = $paginator->lastPage();
$total = $paginator->total();
$perPage = $paginator->perPage();
$from = ($currentPage - 1) * $perPage + 1;
$to = min($currentPage * $perPage, $total);

// Calcola i link da mostrare
$start = max(1, $currentPage - floor($maxLinks / 2));
$end = min($lastPage, $start + $maxLinks - 1);
$start = max(1, $end - $maxLinks + 1);

// Classi per dimensioni
$sizeClasses = [
    'sm' => 'px-2 py-1 text-sm',
    'md' => 'px-3 py-2 text-sm',
    'lg' => 'px-4 py-2 text-base'
];

$baseClasses = [
    'relative',
    'inline-flex',
    'items-center',
    'border',
    'font-medium',
    'transition-colors',
    'duration-200',
    'focus:z-10',
    'focus:outline-none',
    'focus:ring-2',
    'focus:ring-italia-blue-500',
    'focus:ring-offset-2',
    $sizeClasses[$size]
];
@endphp

<nav class="flex items-center justify-between" aria-label="Navigazione pagine">
    @if($showInfo)
        {{-- Info risultati --}}
        <div class="flex flex-1 justify-between sm:hidden">
            <p class="text-sm text-gray-700">
                Risultati <span class="font-medium">{{ $from }}</span> - <span class="font-medium">{{ $to }}</span> di <span class="font-medium">{{ $total }}</span>
            </p>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Mostrando 
                    <span class="font-medium">{{ $from }}</span>
                    -
                    <span class="font-medium">{{ $to }}</span>
                    di
                    <span class="font-medium">{{ $total }}</span>
                    risultati
                </p>
            </div>
        </div>
    @endif
    
    {{-- Controlli paginazione --}}
    <div class="flex items-center space-x-1" @if(!$showInfo) class="w-full justify-center" @endif>
        {{-- Mobile: Precedente/Successivo --}}
        <div class="flex sm:hidden">
            @if ($paginator->onFirstPage())
                <span @class([
                    ...$baseClasses,
                    'border-gray-300',
                    'text-gray-400',
                    'cursor-not-allowed',
                    'bg-gray-50',
                    'rounded-l-md'
                ])
                aria-disabled="true">
                    <span class="sr-only">{{ $previousText }}</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   @class([
                       ...$baseClasses,
                       'border-gray-300',
                       'text-gray-500',
                       'hover:text-gray-700',
                       'hover:bg-gray-50',
                       'bg-white',
                       'rounded-l-md'
                   ])
                   rel="prev"
                   aria-label="{{ $previousText }}">
                    <span class="sr-only">{{ $previousText }}</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
            
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   @class([
                       ...$baseClasses,
                       'border-gray-300',
                       'border-l-0',
                       'text-gray-500',
                       'hover:text-gray-700',
                       'hover:bg-gray-50',
                       'bg-white',
                       'rounded-r-md'
                   ])
                   rel="next"
                   aria-label="{{ $nextText }}">
                    <span class="sr-only">{{ $nextText }}</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span @class([
                    ...$baseClasses,
                    'border-gray-300',
                    'border-l-0',
                    'text-gray-400',
                    'cursor-not-allowed',
                    'bg-gray-50',
                    'rounded-r-md'
                ])
                aria-disabled="true">
                    <span class="sr-only">{{ $nextText }}</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>
        
        {{-- Desktop: Paginazione completa --}}
        <div class="hidden sm:flex">
            {{-- Primo --}}
            @if($currentPage > 3 && $lastPage > $maxLinks)
                <a href="{{ $paginator->url(1) }}" 
                   @class([
                       ...$baseClasses,
                       'border-gray-300',
                       'text-gray-500',
                       'hover:text-gray-700',
                       'hover:bg-gray-50',
                       'bg-white',
                       'rounded-l-md'
                   ])
                   aria-label="{{ $firstText }}">
                    {{ $firstText }}
                </a>
                
                @if($currentPage > 4)
                    <span @class([
                        ...$baseClasses,
                        'border-gray-300',
                        'border-l-0',
                        'text-gray-500',
                        'bg-white'
                    ])>
                        ...
                    </span>
                @endif
            @endif
            
            {{-- Precedente --}}
            @if ($paginator->onFirstPage())
                <span @class([
                    ...$baseClasses,
                    'border-gray-300',
                    'text-gray-400',
                    'cursor-not-allowed',
                    'bg-gray-50',
                    $currentPage <= 3 || $lastPage <= $maxLinks ? 'rounded-l-md' : ''
                ])
                aria-disabled="true">
                    <span class="sr-only">{{ $previousText }}</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" 
                   @class([
                       ...$baseClasses,
                       'border-gray-300',
                       'text-gray-500',
                       'hover:text-gray-700',
                       'hover:bg-gray-50',
                       'bg-white',
                       $currentPage <= 3 || $lastPage <= $maxLinks ? 'rounded-l-md' : 'border-l-0'
                   ])
                   rel="prev"
                   aria-label="{{ $previousText }}">
                    <span class="sr-only">{{ $previousText }}</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
            
            {{-- Numeri pagina --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $currentPage)
                    <span @class([
                        ...$baseClasses,
                        'border-italia-blue-500',
                        'bg-italia-blue-50',
                        'text-italia-blue-600',
                        'border-l-0'
                    ])
                    aria-current="page">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}" 
                       @class([
                           ...$baseClasses,
                           'border-gray-300',
                           'text-gray-500',
                           'hover:text-gray-700',
                           'hover:bg-gray-50',
                           'bg-white',
                           'border-l-0'
                       ])
                       aria-label="Vai alla pagina {{ $page }}">
                        {{ $page }}
                    </a>
                @endif
            @endfor
            
            {{-- Successivo --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" 
                   @class([
                       ...$baseClasses,
                       'border-gray-300',
                       'border-l-0',
                       'text-gray-500',
                       'hover:text-gray-700',
                       'hover:bg-gray-50',
                       'bg-white',
                       ($currentPage >= $lastPage - 2 || $lastPage <= $maxLinks) && $end >= $lastPage ? 'rounded-r-md' : ''
                   ])
                   rel="next"
                   aria-label="{{ $nextText }}">
                    <span class="sr-only">{{ $nextText }}</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span @class([
                    ...$baseClasses,
                    'border-gray-300',
                    'border-l-0',
                    'text-gray-400',
                    'cursor-not-allowed',
                    'bg-gray-50',
                    ($currentPage >= $lastPage - 2 || $lastPage <= $maxLinks) && $end >= $lastPage ? 'rounded-r-md' : ''
                ])
                aria-disabled="true">
                    <span class="sr-only">{{ $nextText }}</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
            
            {{-- Ultimo --}}
            @if($currentPage < $lastPage - 2 && $lastPage > $maxLinks)
                @if($currentPage < $lastPage - 3)
                    <span @class([
                        ...$baseClasses,
                        'border-gray-300',
                        'border-l-0',
                        'text-gray-500',
                        'bg-white'
                    ])>
                        ...
                    </span>
                @endif
                
                <a href="{{ $paginator->url($lastPage) }}" 
                   @class([
                       ...$baseClasses,
                       'border-gray-300',
                       'border-l-0',
                       'text-gray-500',
                       'hover:text-gray-700',
                       'hover:bg-gray-50',
                       'bg-white',
                       'rounded-r-md'
                   ])
                   aria-label="{{ $lastText }}">
                    {{ $lastText }}
                </a>
            @endif
        </div>
    </div>
    
    @if($showJumper && $lastPage > 10)
        {{-- Page jumper --}}
        <div class="ml-4 flex items-center space-x-2">
            <label for="page-jumper" class="text-sm text-gray-700">Vai alla pagina:</label>
            <input 
                id="page-jumper"
                type="number" 
                min="1" 
                max="{{ $lastPage }}"
                value="{{ $currentPage }}"
                class="w-16 px-2 py-1 text-sm border border-gray-300 rounded-md focus:ring-italia-blue-500 focus:border-italia-blue-500"
                onchange="if(this.value >= 1 && this.value <= {{ $lastPage }}) window.location.href = '{{ $paginator->url(1) }}'.replace('page=1', 'page=' + this.value)"
                aria-label="Numero pagina"
            >
        </div>
    @endif
</nav>

{{-- 
Utilizzo:

<!-- Paginazione semplice -->
<x-pagination :paginator="$users" />

<!-- Paginazione con opzioni personalizzate -->
<x-pagination 
    :paginator="$users"
    size="lg"
    :show-info="false"
    :show-jumper="true"
    :max-links="5"
/>

<!-- In un controller o livewire component -->
$users = User::paginate(15);
return view('users.index', compact('users'));
--}}