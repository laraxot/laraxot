{{--
    Topics Grid Block - Universal reusable component
    
    USAGE:
    <x-pub_theme::components.blocks.topics-grid.default :data="['topics' => [...], 'title' => 'Title']" />
    
    DATA STRUCTURE:
    [
        'title' => 'Esplora per argomento',
        'topics' => [
            ['title' => 'Agricoltura', 'url' => '#', 'description' => '...'],
            ...
        ]
    ]
    
    BLOCK TYPE: topics-grid (UNIVERSAL, NOT page-specific)
    VIEW: pub_theme::components.blocks.topics-grid.default
    
    DESIGN COMUNI REFERENCE: argomenti.html - "Esplora per argomento" section
    - Uses cmp-card-simple pattern
    - Card with shadow-sm, border-light
    - Title with t-primary title-xlarge
    - Description with text-secondary
--}}

@props(['data' => []])

@php
    $title = $data['title'] ?? 'Esplora per argomento';
    $topics = $data['topics'] ?? [];
@endphp

<div class="py-5" id="argomento">
    @if($title)
        <h2 class="title-xxlarge mb-4">{{ $title }}</h2>
    @endif
    
    <div class="row g-4">
        @foreach($topics as $topic)
            <div class="col-md-6 col-xl-4">
                <div class="cmp-card-simple card-wrapper pb-0 rounded border border-light">
                    <div class="card shadow-sm rounded">
                        <div class="card-body">
                            <a href="{{ $topic['url'] ?? '#' }}" class="text-decoration-none" data-element="topic-element">
                                <h3 class="card-title t-primary title-xlarge">
                                    {{ $topic['title'] ?? 'Topic' }}
                                </h3>
                            </a>
                            @if(isset($topic['description']))
                                <p class="text-secondary mb-0 description">
                                    {{ $topic['description'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
