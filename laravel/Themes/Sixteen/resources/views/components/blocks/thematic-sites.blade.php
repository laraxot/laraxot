@props(['data' => []])

{{-- 
    Thematic Sites Component - Design Comuni Style
    Usage: <x-blocks.thematic-sites :data="$sitesData" />
    
    Data structure:
    - title: string
    - sites: array
--}}

@php
    $title = $data['title'] ?? 'Siti tematici';
    $sites = $data['sites'] ?? [
        [
            'title' => 'MOBILITÀ IN COMUNE',
            'description' => 'Sito del turismo e della Città Metropolitana',
            'url' => '#',
            'icon' => 'heroicon-o-truck',
        ],
        [
            'title' => 'TURISMO',
            'description' => 'Attività turistiche',
            'url' => '#',
            'icon' => 'heroicon-o-globe-alt',
        ],
        [
            'title' => 'MUSEI CIVICI',
            'description' => 'Musei ed eventi culturali',
            'url' => '#',
            'icon' => 'heroicon-o-building-library',
        ],
    ];
@endphp

<section class="thematic-sites py-8">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="title-xxlarge mb-6">{{ $title }}</h2>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($sites as $site)
            <div class="col-12 col-md-4">
                <a href="{{ $site['url'] }}" class="block p-6 border-2 border-gray-200 rounded-lg hover:border-primary hover:shadow-md transition-all">
                    <div class="flex items-start gap-4">
                        <x-filament::icon :icon="$site['icon']" class="w-12 h-12 text-primary flex-shrink-0" />
                        <div>
                            <h3 class="font-semibold text-lg mb-2">{{ $site['title'] }}</h3>
                            <p class="text-gray-600">{{ $site['description'] }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
