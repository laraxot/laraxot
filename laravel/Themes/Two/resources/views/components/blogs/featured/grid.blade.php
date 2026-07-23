@php
declare(strict_types=1);

@props([
    'title' => 'Articoli in Evidenza',
    'articles' => [
        [
            'id' => 1,
            'title' => 'Nuovi Requisiti per la Radioprotezione: Guida Completa',
            'excerpt' => 'Scopri i nuovi requisiti normativi per la radioprotezione nelle strutture sanitarie e come adeguarsi alle ultime direttive.',
            'author' => ['name' => 'Dr. Marco Rossi', 'avatar' => 'https://i.pravatar.cc/150?img=1'],
            'date' => '15 Gennaio 2025',
            'category' => ['name' => 'Normativa', 'slug' => 'normativa', 'color' => 'blue'],
            'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&q=80',
            'reading_time' => '8 min',
            'views' => 1245,
            'likes' => 89,
            'comments' => 23,
            'badge' => 'Trending',
            'badge_color' => 'red'
        ],
        [
            'id' => 2,
            'title' => 'Controllo Qualità Elettromedicali: Best Practice',
            'excerpt' => 'Linee guida essenziali per il controllo qualità degli elettromedicali in radiologia diagnostica.',
            'author' => ['name' => 'Ing. Laura Bianchi', 'avatar' => 'https://i.pravatar.cc/150?img=2'],
            'date' => '10 Gennaio 2025',
            'category' => ['name' => 'Elettromedicali', 'slug' => 'elettromedicali', 'color' => 'green'],
            'image' => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?w=800&q=80',
            'reading_time' => '6 min',
            'views' => 987,
            'likes' => 67,
            'comments' => 18,
            'badge' => 'Featured',
            'badge_color' => 'purple'
        ],
        [
            'id' => 3,
            'title' => 'Radioprotezione Veterinaria: Normative e Applicazioni',
            'excerpt' => 'Come applicare correttamente le normative di radioprotezione nelle strutture veterinarie.',
            'author' => ['name' => 'Dr. Sara Verdi', 'avatar' => 'https://i.pravatar.cc/150?img=3'],
            'date' => '5 Gennaio 2025',
            'category' => ['name' => 'Veterinaria', 'slug' => 'veterinaria', 'color' => 'orange'],
            'image' => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=800&q=80',
            'reading_time' => '10 min',
            'views' => 756,
            'likes' => 54,
            'comments' => 12,
            'badge' => 'New',
            'badge_color' => 'green'
        ]
    ]
])
?>

{{-- Featured Articles Grid --}}
<section class="py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ $title }}
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                I nostri articoli più letti e condivisi dalla community
            </p>
        </div>
        
        {{-- Featured Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($articles as $article)
                <article class="group bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:-translate-y-2">
                    {{-- Badge --}}
                    @if(isset($article['badge']))
                        <div class="absolute top-4 left-4 z-10">
                            <span class="px-3 py-1 text-xs font-bold text-white rounded-full shadow-lg
                                {{ $article['badge_color'] === 'red' ? 'bg-red-500' : ($article['badge_color'] === 'purple' ? 'bg-purple-500' : 'bg-green-500') }}">
                                {{ $article['badge'] }}
                            </span>
                        </div>
                    @endif
                    
                    {{-- Image --}}
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $article['image'] }}" 
                             alt="{{ $article['title'] }}" 
                             class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110"
                             loading="lazy">
                        
                        {{-- Category Badge --}}
                        <div class="absolute bottom-4 left-4">
                            <span class="px-3 py-1 text-xs font-semibold text-white rounded-full bg-{{ $article['category']['color'] }}-600 shadow-lg">
                                {{ $article['category']['name'] }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Content --}}
                    <div class="p-6">
                        {{-- Title --}}
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">
                            <a href="/it/blog/{{ $article['id'] }}">
                                {{ $article['title'] }}
                            </a>
                        </h3>
                        
                        {{-- Excerpt --}}
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ $article['excerpt'] }}
                        </p>
                        
                        {{-- Author & Meta --}}
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $article['author']['avatar'] }}" 
                                     alt="{{ $article['author']['name'] }}" 
                                     class="w-8 h-8 rounded-full object-cover">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $article['author']['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $article['date'] }}</p>
                                </div>
                            </div>
                            
                            <span class="text-xs text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $article['reading_time'] }}
                            </span>
                        </div>
                        
                        {{-- Stats --}}
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ $article['views'] }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    {{ $article['likes'] }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    {{ $article['comments'] }}
                                </span>
                            </div>
                            
                            <a href="/it/blog/{{ $article['id'] }}" 
                               class="text-blue-600 font-semibold text-sm hover:text-blue-800 transition-colors duration-200">
                                Leggi di più →
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>