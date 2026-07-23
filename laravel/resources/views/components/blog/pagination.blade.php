@php
    $currentPage = $currentPage ?? 1;
    $totalPages = $totalPages ?? 12;
    $totalItems = $totalItems ?? 118;
    $itemsPerPage = $itemsPerPage ?? 10;
    $showFirstLast = $showFirstLast ?? true;
    $showPrevNext = $showPrevNext ?? true;
    $maxVisiblePages = $maxVisiblePages ?? 7;
    $baseUrl = $baseUrl ?? request()->url();
    $pageParam = $pageParam ?? 'page';
@endphp

@if($totalPages > 1)
    <nav class="flex items-center justify-between" aria-label="Navigazione articoli">
        {{-- Results Count --}}
        <div class="flex-1 flex justify-between sm:hidden">
            <p class="text-sm text-gray-700">
                Mostrando 
                <span class="font-medium">{{ min(($currentPage - 1) * $itemsPerPage + 1, $totalItems) }}</span> 
                a 
                <span class="font-medium">{{ min($currentPage * $itemsPerPage, $totalItems) }}</span> 
                di 
                <span class="font-medium">{{ $totalItems }}</span> 
                risultati
            </p>
        </div>
        
        {{-- Desktop Pagination --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Articoli da 
                    <span class="font-medium">{{ min(($currentPage - 1) * $itemsPerPage + 1, $totalItems) }}</span> 
                    a 
                    <span class="font-medium">{{ min($currentPage * $itemsPerPage, $totalItems) }}</span> 
                    di 
                    <span class="font-medium">{{ $totalItems }}</span>
                </p>
            </div>
            
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    {{-- First Page --}}
                    @if($showFirstLast && $currentPage > 1)
                        <a 
                            href="{{ $baseUrl }}?{{ $pageParam }}=1"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors"
                            aria-label="Prima pagina"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                            </svg>
                        </a>
                    @endif
                    
                    {{-- Previous Page --}}
                    @if($showPrevNext && $currentPage > 1)
                        <a 
                            href="{{ $baseUrl }}?{{ $pageParam }}={{ $currentPage - 1 }}"
                            class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors"
                            aria-label="Pagina precedente"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                    @endif
                    
                    {{-- Page Numbers --}}
                    @php
                        $startPage = max(1, $currentPage - floor($maxVisiblePages / 2));
                        $endPage = min($totalPages, $startPage + $maxVisiblePages - 1);
                        
                        if ($endPage - $startPage < $maxVisiblePages - 1) {
                            $startPage = max(1, $endPage - $maxVisiblePages + 1);
                        }
                    @endphp
                    
                    {{-- Show ellipsis if needed --}}
                    @if($startPage > 1)
                        <a 
                            href="{{ $baseUrl }}?{{ $pageParam }}=1"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            1
                        </a>
                        
                        @if($startPage > 2)
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                ...
                            </span>
                        @endif
                    @endif
                    
                    {{-- Visible page numbers --}}
                    @for($i = $startPage; $i <= $endPage; $i++)
                        @if($i == $currentPage)
                            <span aria-current="page" class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600">
                                {{ $i }}
                            </span>
                        @else
                            <a 
                                href="{{ $baseUrl }}?{{ $pageParam }}={{ $i }}"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                {{ $i }}
                            </a>
                        @endif
                    @endfor
                    
                    {{-- Show ellipsis if needed --}}
                    @if($endPage < $totalPages)
                        @if($endPage < $totalPages - 1)
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                ...
                            </span>
                        @endif
                        
                        <a 
                            href="{{ $baseUrl }}?{{ $pageParam }}={{ $totalPages }}"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            {{ $totalPages }}
                        </a>
                    @endif
                    
                    {{-- Next Page --}}
                    @if($showPrevNext && $currentPage < $totalPages)
                        <a 
                            href="{{ $baseUrl }}?{{ $pageParam }}={{ $currentPage + 1 }}"
                            class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors"
                            aria-label="Pagina successiva"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                    
                    {{-- Last Page --}}
                    @if($showFirstLast && $currentPage < $totalPages)
                        <a 
                            href="{{ $baseUrl }}?{{ $pageParam }}={{ $totalPages }}"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors"
                            aria-label="Ultima pagina"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </nav>
            </div>
        </div>
        
        {{-- Mobile Pagination --}}
        <div class="flex justify-between flex-1 sm:hidden">
            {{-- Previous --}}
            @if($currentPage > 1)
                <a 
                    href="{{ $baseUrl }}?{{ $pageParam }}={{ $currentPage - 1 }}"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors"
                >
                    Precedente
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-100 cursor-not-allowed">
                    Precedente
                </span>
            @endif
            
            {{-- Next --}}
            @if($currentPage < $totalPages)
                <a 
                    href="{{ $baseUrl }}?{{ $pageParam }}={{ $currentPage + 1 }}"
                    class="relative ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors"
                >
                    Successivo
                </a>
            @else
                <span class="relative ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-100 cursor-not-allowed">
                    Successivo
                </span>
            @endif
        </div>
    </nav>
    
    {{-- Jump to Page --}}
    <div class="mt-6 flex items-center justify-center">
        <div class="flex items-center space-x-2 text-sm text-gray-600">
            <span>Vai alla pagina:</span>
            <form action="{{ $baseUrl }}" method="GET" class="flex items-center space-x-1">
                <input 
                    type="number" 
                    name="{{ $pageParam }}" 
                    min="1" 
                    max="{{ $totalPages }}" 
                    value="{{ $currentPage }}"
                    class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                    Vai
                </button>
            </form>
            <span>di {{ $totalPages }}</span>
        </div>
    </div>
@endif