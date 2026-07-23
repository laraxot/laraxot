@props([
    'title' => 'Blog',
    'subtitle' => '',
    'search_placeholder' => 'Cerca articoli...',
    'featured_categories' => [],
])

@php
    $searchPlaceholder = $search_placeholder;
    $featuredCategories = $featured_categories;
@endphp

<section class="relative bg-gradient-to-br from-purple-900 via-purple-800 to-purple-900 py-20 lg:py-28">
    <div class="absolute inset-0 bg-black/30"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            {{-- Title --}}
            <h1 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                {{ $title }}
            </h1>
            
            {{-- Subtitle --}}
            @if(!empty($subtitle))
                <p class="text-xl text-purple-100 mb-8">
                    {{ $subtitle }}
                </p>
            @endif
            
            {{-- Search Bar --}}
            <div class="max-w-2xl mx-auto mb-12">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="{{ $searchPlaceholder }}"
                        class="w-full px-6 py-4 rounded-full text-gray-900 bg-white shadow-lg focus:outline-none focus:ring-4 focus:ring-purple-300 transition-all"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-6 top-1/2 -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            
            {{-- Featured Categories --}}
            @if(!empty($featuredCategories))
                <div class="flex flex-wrap justify-center gap-3 max-w-4xl mx-auto">
                    @foreach($featuredCategories as $category)
                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm text-white text-sm font-medium border border-white/30 hover:bg-white/30 transition-all cursor-pointer">
                            <span class="w-2 h-2 rounded-full bg-{{ $category['color'] ?? 'blue' }}-400 mr-2"></span>
                            {{ $category['name'] ?? '' }}
                            <span class="ml-2 text-xs opacity-75">({{ $category['count'] ?? 0 }})</span>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
