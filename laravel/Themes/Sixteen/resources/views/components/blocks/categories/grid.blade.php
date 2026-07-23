{{-- Categories Grid Block - Bootstrap Italia Exact Replica
Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/lista-categorie.html
--}}
@props(['data' => []])

@php
    $title = $data['title'] ?? 'Esplora per categoria';
    $categories = $data['categories'] ?? $data['items'] ?? [];
@endphp

<div class="container py-5">
    @if($title)
        <h2 class="title-xxlarge mb-4">{{ $title }}</h2>
    @endif
    
    <div class="row g-4">
        @foreach($categories as $category)
            <div class="col-md-6 col-lg-4">
                <div class="cmp-card-simple card-wrapper pb-0 rounded border border-light">
                    <div class="card shadow-sm rounded">
                        <div class="card-body">
                            <a href="{{ $category['url'] ?? '#' }}" class="text-decoration-none" data-element="category-element">
                                <h3 class="card-title t-primary title-xlarge">
                                    {{ $category['name'] ?? $category['title'] ?? 'Categoria' }}
                                </h3>
                            </a>
                            @if(isset($category['description']))
                                <p class="text-secondary mb-0 description">
                                    {{ $category['description'] }}
                                </p>
                            @endif
                            @if(isset($category['count']))
                                <p class="text-muted mb-0 mt-2 small">
                                    {{ $category['count'] }} contenuti
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
