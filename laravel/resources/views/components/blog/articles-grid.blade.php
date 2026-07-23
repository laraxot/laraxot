@props([
    'articles' => [],
    'title' => null,
    'subtitle' => null,
    'layout' => 'grid-3', // grid-2, grid-3, grid-4
    'show_filters' => false,
    'show_pagination' => false,
    'articles_per_page' => 12,
])

@php
    $layouts = [
        'grid-2' => 'grid-cols-1 md:grid-cols-2',
        'grid-3' => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        'grid-4' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
    ];
    $gridClass = $layouts[$layout] ?? $layouts['grid-3'];
@endphp

@if(!empty($articles))
    <section class="py-16 bg-white">
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

            {{-- Filters (if enabled) --}}
            @if($show_filters)
                <div class="mb-8 flex flex-wrap items-center justify-center gap-2">
                    <button class="px-4 py-2 rounded-full bg-blue-600 text-white font-medium text-sm">
                        Tutti
                    </button>
                    <button class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 font-medium text-sm hover:bg-gray-200 transition-colors">
                        Più Recenti
                    </button>
                    <button class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 font-medium text-sm hover:bg-gray-200 transition-colors">
                        Più Popolari
                    </button>
                    <button class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 font-medium text-sm hover:bg-gray-200 transition-colors">
                        In Evidenza
                    </button>
                </div>
            @endif

            {{-- Articles Grid --}}
            <div class="grid {{ $gridClass }} gap-8">
                @foreach($articles as $article)
                    <x-blog.article-card :article="$article" />
                @endforeach
            </div>

            {{-- Pagination (if enabled) --}}
            @if($show_pagination)
                <div class="mt-12 flex justify-center">
                    <x-blog.pagination 
                        :current_page="1" 
                        :total_pages="ceil(count($articles) / $articles_per_page)"
                    />
                </div>
            @endif
        </div>
    </section>
@endif