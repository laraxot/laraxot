@php
    $article = $article ?? [];
    $showExcerpt = $showExcerpt ?? true;
    $showMeta = $showMeta ?? true;
    $cardStyle = $cardStyle ?? 'default'; // default, compact, featured
    
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
        'Novità' => 'indigo',
        'News' => 'indigo',
    ];
    
    $category = $article['category'] ?? 'General';
    $colorKey = $categoryColors[$category] ?? 'blue';
    
    $gradientClasses = match($colorKey) {
        'blue' => 'from-blue-500 to-blue-700',
        'green' => 'from-green-500 to-green-700',
        'orange' => 'from-orange-500 to-orange-700',
        'purple' => 'from-purple-500 to-purple-700',
        'teal' => 'from-teal-500 to-teal-700',
        'indigo' => 'from-indigo-500 to-indigo-700',
        default => 'from-blue-500 to-blue-700',
    };
    
    $badgeClasses = match($colorKey) {
        'blue' => 'bg-blue-100 text-blue-800 border-blue-200',
        'green' => 'bg-green-100 text-green-800 border-green-200',
        'orange' => 'bg-orange-100 text-orange-800 border-orange-200',
        'purple' => 'bg-purple-100 text-purple-800 border-purple-200',
        'teal' => 'bg-teal-100 text-teal-800 border-teal-200',
        'indigo' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
        default => 'bg-blue-100 text-blue-800 border-blue-200',
    };
@endphp

<article class="article-card group bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden {{ $cardStyle === 'featured' ? 'md:col-span-2 lg:col-span-2' : '' }}" data-category="{{ Str::slug($category) }}">
    {{-- Image Section --}}
    @if(!empty($article['image']))
        <div class="relative {{ $cardStyle === 'featured' ? 'h-64 md:h-80' : 'h-48' }} overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br {{ $gradientClasses }} opacity-90"></div>
            <img 
                src="{{ $article['image'] }}" 
                alt="{{ $article['title'] ?? '' }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 mix-blend-overlay"
                loading="lazy"
            >
            
            {{-- Category Badge --}}
            @if(!empty($article['category']))
                <div class="absolute top-4 left-4 z-10">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $badgeClasses }} border backdrop-blur-sm">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                        </svg>
                        {{ $article['category'] }}
                    </span>
                </div>
            @endif
            
            {{-- Reading Time Badge --}}
            @if(!empty($article['reading_time']))
                <div class="absolute top-4 right-4 z-10">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/90 backdrop-blur-sm text-gray-700">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $article['reading_time'] }}
                    </span>
                </div>
            @endif
        </div>
    @endif
    
    {{-- Content Section --}}
    <div class="p-6">
        {{-- Title --}}
        @if(!empty($article['title']))
            <h3 class="text-xl {{ $cardStyle === 'featured' ? 'text-2xl md:text-3xl' : '' }} font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                <a href="{{ $article['url'] ?? '#' }}" class="hover:underline">
                    {{ $article['title'] }}
                </a>
            </h3>
        @endif
        
        {{-- Excerpt --}}
        @if($showExcerpt && !empty($article['excerpt']))
            <p class="text-gray-600 text-sm mb-4 {{ $cardStyle === 'featured' ? 'text-base line-clamp-3' : 'line-clamp-3' }} leading-relaxed">
                {{ $article['excerpt'] }}
            </p>
        @endif
        
        {{-- Tags --}}
        @if(!empty($article['tags']) && is_array($article['tags']))
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($article['tags'] as $tag)
                    <a href="#" class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                        #{{ $tag }}
                    </a>
                @endforeach
            </div>
        @endif
        
        {{-- Meta Information --}}
        @if($showMeta)
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="flex items-center space-x-4 text-xs text-gray-500">
                    {{-- Date --}}
                    @if(!empty($article['date']))
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $article['date'] }}
                        </span>
                    @endif
                    
                    {{-- Author --}}
                    @if(!empty($article['author']))
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $article['author'] }}
                        </span>
                    @endif
                    
                    {{-- Comments Count --}}
                    @if(isset($article['comments_count']))
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ $article['comments_count'] }} commenti
                        </span>
                    @endif
                </div>
                
                {{-- Read More Link --}}
                <a href="{{ $article['url'] ?? '#' }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-sm group-hover:translate-x-1 transition-all">
                    Leggi tutto
                    <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>
    
    {{-- Social Sharing Overlay (appears on hover) --}}
    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center pointer-events-none">
        <div class="flex space-x-3 pointer-events-auto">
            <button class="p-2 bg-white/90 backdrop-blur-sm rounded-full hover:bg-white transition-colors" title="Condividi su Facebook">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
            </button>
            <button class="p-2 bg-white/90 backdrop-blur-sm rounded-full hover:bg-white transition-colors" title="Condividi su Twitter">
                <svg class="w-5 h-5 text-sky-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                </svg>
            </button>
            <button class="p-2 bg-white/90 backdrop-blur-sm rounded-full hover:bg-white transition-colors" title="Condividi su LinkedIn">
                <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
            </button>
        </div>
    </div>
</article>