@props([
    'title' => 'Articoli Recenti',
    'subtitle' => '',
    'articles' => [],
])

@php
    
    $categoryColors = [
        'Radioprotezione' => 'blue',
        'Radiation Protection' => 'blue',
        'Normativa' => 'green',
        'Regulations' => 'green',
        'Elettromedicali' => 'orange',
        'Electromedical' => 'orange',
        'Veterinaria' => 'purple',
        'Veterinary' => 'purple',
        'Guide Pratiche' => 'teal',
        'Practical Guides' => 'teal',
    ];
@endphp

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            @if(!empty($subtitle))
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- Articles Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
            @foreach($articles as $index => $article)
                @php
                    $category = $article['category'] ?? 'General';
                    $colorKey = $categoryColors[$category] ?? 'blue';
                    $borderColor = match($colorKey) {
                        'blue' => 'border-[#1E5A96]',
                        'green' => 'border-[#2D8659]',
                        'orange' => 'border-[#E67E22]',
                        'purple' => 'border-purple-600',
                        'teal' => 'border-teal-600',
                        default => 'border-[#1E5A96]',
                    };
                    $bgColor = match($colorKey) {
                        'blue' => 'bg-blue-50 text-[#1E5A96]',
                        'green' => 'bg-green-50 text-[#2D8659]',
                        'orange' => 'bg-orange-50 text-[#E67E22]',
                        'purple' => 'bg-purple-50 text-purple-600',
                        'teal' => 'bg-teal-50 text-teal-600',
                        default => 'bg-blue-50 text-[#1E5A96]',
                    };
                @endphp
                
                <article class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 border-t-4 {{ $borderColor }} group">
                    {{-- Article Image --}}
                    @if(!empty($article['image']))
                        <div class="relative h-56 overflow-hidden">
                            <img 
                                src="{{ $article['image'] }}" 
                                alt="{{ $article['title'] }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            >
                            {{-- Category Badge --}}
                            @if(!empty($article['category']))
                                <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $bgColor }}">
                                    {{ $article['category'] }}
                                </span>
                            @endif
                        </div>
                    @endif
                    
                    {{-- Article Content --}}
                    <div class="p-6">
                        {{-- Title --}}
                        @if(!empty($article['title']))
                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-[#1E5A96] transition-colors">
                                <a href="{{ $article['url'] ?? '#' }}">
                                    {{ $article['title'] }}
                                </a>
                            </h3>
                        @endif
                        
                        {{-- Excerpt --}}
                        @if(!empty($article['excerpt']))
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                                {{ $article['excerpt'] }}
                            </p>
                        @endif
                        
                        {{-- Meta Info --}}
                        <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-4">
                                @if(!empty($article['date']))
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                                            <line x1="16" x2="16" y1="2" y2="6"></line>
                                            <line x1="8" x2="8" y1="2" y2="6"></line>
                                            <line x1="3" x2="21" y1="10" y2="10"></line>
                                        </svg>
                                        {{ $article['date'] }}
                                    </span>
                                @endif
                                @if(!empty($article['reading_time']))
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12,6 12,12 16,14"></polyline>
                                        </svg>
                                        {{ $article['reading_time'] }}
                                    </span>
                                @endif
                            </div>
                            
                            <a href="{{ $article['url'] ?? '#' }}" class="inline-flex items-center text-[#2D8659] hover:text-[#1E5A96] font-semibold group-hover:translate-x-1 transition-all">
                                Leggi
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        
        {{-- View All Button --}}
        @if(count($articles) > 0)
            <div class="text-center mt-12">
                <a href="/it/blog" class="inline-flex items-center justify-center bg-[#1E5A96] hover:bg-[#164575] text-white text-lg px-8 py-4 rounded-lg font-semibold shadow-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                    </svg>
                    Vedi Tutti gli Articoli
                </a>
            </div>
        @endif
    </div>
</section>
