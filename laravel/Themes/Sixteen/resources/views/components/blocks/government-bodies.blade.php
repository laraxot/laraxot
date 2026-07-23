@props(['data' => []])

{{-- 
    Government Bodies Component - Design Comuni Style
    Usage: <x-blocks.government-bodies :data="$govData" />
    
    Data structure:
    - title: string
    - bodies: array
--}}

@php
    $title = $data['title'] ?? 'Organi di governo';
    $bodies = $data['bodies'] ?? [
        [
            'title' => 'MARIO ROSSI',
            'subtitle' => 'Il Sindaco della città',
            'description' => '',
            'image' => 'https://via.placeholder.com/300x300',
            'url' => '#',
            'link_text' => 'Vai alla pagina',
        ],
        [
            'title' => 'LA GIUNTA COMUNALE',
            'subtitle' => 'Organo collegiale',
            'description' => 'La Giunta Comunale è un organo collegiale della durata di 5 anni previsto dalla legge.',
            'image' => 'https://via.placeholder.com/300x300',
            'url' => '#',
            'link_text' => 'Vai alla pagina',
        ],
        [
            'title' => 'IL CONSIGLIO COMUNALE',
            'subtitle' => 'Organo elettivo',
            'description' => 'Il Consiglio Comunale è un organo elettivo della durata di 5 anni previsto dalla legge.',
            'image' => 'https://via.placeholder.com/300x300',
            'url' => '#',
            'link_text' => 'Vai alla pagina',
        ],
    ];
@endphp

<section class="government-bodies py-8 bg-gray-50">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="title-xxlarge mb-6">{{ $title }}</h2>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($bodies as $body)
            <div class="col-12 col-md-4">
                <div class="card card-bg border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                    @if(isset($body['image']) && $body['image'])
                    <img src="{{ $body['image'] }}" alt="{{ $body['title'] }}" class="w-full h-48 object-cover" />
                    @endif
                    <div class="card-body p-6">
                        <h3 class="h5 font-bold mb-2">{{ $body['title'] }}</h3>
                        @if($body['subtitle'])
                        <p class="text-sm text-gray-600 mb-3">{{ $body['subtitle'] }}</p>
                        @endif
                        @if($body['description'])
                        <p class="text-gray-600 mb-4">{{ $body['description'] }}</p>
                        @endif
                        <a href="{{ $body['url'] }}" class="text-primary hover:text-primary-dark font-medium inline-flex items-center gap-1">
                            {{ $body['link_text'] }}
                            <x-filament::icon icon="heroicon-m-arrow-right" class="w-4 h-4" />
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
