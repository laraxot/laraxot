@props([
    'articles' => [],
    'title' => null,
    'subtitle' => null,
    'columns' => 3,
])

@php
    $columns = min(max($columns, 1), 3); // Ensure columns is between 1 and 3
    $gridCols = [
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
    ];
@endphp

@if(!empty($articles))
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            @if($title || $subtitle)
                <div class="text-center mb-12">
                    @if($title)
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
                    @endif
                    @if($subtitle)
                        <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif

            {{-- Featured Articles Grid --}}
            <div class="grid {{ $gridCols[$columns] }} gap-8">
                @foreach($articles as $article)
                    <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        {{-- Article Image --}}
                        @if(!empty($article['image']))
                            <div class="relative h-48 overflow-hidden">
                                <img
                                    src="{{ $article['image'] }}"
                                    alt="{{ $article['title'] }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                
                                {{-- Category Badge --}}
                                @if(!empty($article['category']))
                                    <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/90 backdrop-blur-sm text-gray-900">
                                        {{ $article['category'] }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        {{-- Article Content --}}
                        <div class="p-6">
                            {{-- Title --}}
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                                @if(!empty($article['url']))
                                    <a href="{{ $article['url'] }}">{{ $article['title'] }}</a>
                                @else
                                    {{ $article['title'] }}
                                @endif
                            </h3>

                            {{-- Excerpt --}}
                            @if(!empty($article['excerpt']))
                                <p class="text-gray-600 mb-4 line-clamp-3">{{ $article['excerpt'] }}</p>
                            @endif

                            {{-- Meta --}}
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <div class="flex items-center space-x-4">
                                    @if(!empty($article['date']))
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $article['date'] }}
                                        </span>
                                    @endif
                                    @if(!empty($article['reading_time']))
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $article['reading_time'] }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Engagement Stats --}}
                                <div class="flex items-center space-x-3">
                                    @if(!empty($article['views_count']))
                                        <span class="flex items-center text-gray-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            {{ $article['views_count'] }}
                                        </span>
                                    @endif
                                    @if(!empty($article['likes_count']))
                                        <span class="flex items-center text-red-400">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                            {{ $article['likes_count'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Author --}}
                            @if(!empty($article['author']))
                                <div class="mt-4 pt-4 border-t border-gray-200 flex items-center">
                                    @if(!empty($article['author_avatar']))
                                        <img
                                            src="{{ $article['author_avatar'] }}"
                                            alt="{{ $article['author'] }}"
                                            class="w-8 h-8 rounded-full mr-3 object-cover"
                                        >
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $article['author'] }}</p>
                                        @if(!empty($article['author_role']))
                                            <p class="text-xs text-gray-500">{{ $article['author_role'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif