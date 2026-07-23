@props([
    'title' => 'Tutti gli Articoli',
    'subtitle' => '',
    'layout' => 'grid',
    'columns' => 3,
    'show_pagination' => true,
    'articles_per_page' => 12,
    'articles' => [],
])

@php
    $categoryColors = [
        'blue' => 'bg-[#1E5A96]',
        'green' => 'bg-[#2D8659]',
        'orange' => 'bg-[#E67E22]',
        'teal' => 'bg-teal-600',
        'purple' => 'bg-purple-600',
        'indigo' => 'bg-indigo-600',
        'red' => 'bg-red-600',
    ];
@endphp

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if(!empty($title))
            <div class="mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $title }}</h2>
                @if(!empty($subtitle))
                    <p class="text-gray-600">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($articles as $article)
                @php
                    $catColor = $categoryColors[$article['category_color'] ?? 'blue'] ?? 'bg-[#1E5A96]';
                @endphp
                <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 group">
                    @if(!empty($article['image']))
                        <div class="relative h-52 overflow-hidden">
                            <img
                                src="{{ $article['image'] }}"
                                alt="{{ $article['title'] ?? '' }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            >
                            @if(!empty($article['category']))
                                <span class="absolute top-4 left-4 px-3 py-1 rounded text-xs font-semibold text-white {{ $catColor }}">
                                    {{ $article['category'] }}
                                </span>
                            @endif
                        </div>
                    @endif

                    <div class="p-6">
                        @if(!empty($article['title']))
                            <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-[#1E5A96] transition-colors">
                                <a href="{{ $article['url'] ?? '#' }}">{{ $article['title'] }}</a>
                            </h3>
                        @endif

                        @if(!empty($article['excerpt']))
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">{{ $article['excerpt'] }}</p>
                        @endif

                        <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-4">
                                @if(!empty($article['date']))
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect>
                                            <line x1="16" x2="16" y1="2" y2="6"></line>
                                            <line x1="8" x2="8" y1="2" y2="6"></line>
                                            <line x1="3" x2="21" y1="10" y2="10"></line>
                                        </svg>
                                        {{ $article['date'] }}
                                    </span>
                                @endif
                                @if(!empty($article['reading_time']))
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12,6 12,12 16,14"></polyline>
                                        </svg>
                                        {{ $article['reading_time'] }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ $article['url'] ?? '#' }}" class="inline-flex items-center text-[#1E5A96] hover:text-[#164575] font-semibold transition-colors">
                                Leggi Articolo
                                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
