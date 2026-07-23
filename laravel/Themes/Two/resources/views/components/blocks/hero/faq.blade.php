@props([
    'title' => 'Domande Frequenti',
    'subtitle' => '',
    'search_placeholder' => 'Cerca una domanda...',
    'stats' => [],
])

@php
    $searchPlaceholder = $search_placeholder;
@endphp

<section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 py-20 lg:py-28">
    <div class="absolute inset-0 bg-black/30"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            {{-- Title --}}
            <h1 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                {{ $title }}
            </h1>
            
            {{-- Subtitle --}}
            @if(!empty($subtitle))
                <p class="text-xl text-blue-100 mb-8">
                    {{ $subtitle }}
                </p>
            @endif
            
            {{-- Search Bar --}}
            <div class="max-w-2xl mx-auto mb-12">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="{{ $searchPlaceholder }}"
                        class="w-full px-6 py-4 rounded-full text-gray-900 bg-white shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-6 top-1/2 -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            
            {{-- Stats --}}
            @if(!empty($stats))
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 max-w-4xl mx-auto">
                    @foreach($stats as $stat)
                        <div class="text-center">
                            <div class="text-3xl lg:text-4xl font-bold text-white mb-2">
                                {{ $stat['value'] ?? '' }}
                            </div>
                            <div class="text-sm text-blue-200">
                                {{ $stat['label'] ?? '' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
