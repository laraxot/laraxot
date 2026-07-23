{{--
    Topics Highlight (In Evidenza) Block - Bootstrap Italia Exact Replica
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/argomenti.html
    Uses it-grid-item-wrapper with image overlay style
--}}
@props(['data' => []])

@php
    $title = $data['title'] ?? 'In evidenza';
    $items = $data['items'] ?? $data['topics'] ?? [];
@endphp

<div class="py-5">
    <h2 class="title-xxlarge mb-4">{{ $title }}</h2>
    <div class="row g-4">
        @foreach($items as $topic)
        <div class="col-sm-6 col-lg-4">
            <div class="it-grid-item-wrapper it-grid-item-overlay">
                <a href="{{ $topic['url'] ?? '#' }}">
                    <div class="img-responsive-wrapper">
                        <div class="img-responsive">
                            <div class="img-wrapper">
                                <img src="{{ $topic['image'] ?? 'https://picsum.photos/376/488' }}" alt="{{ $topic['title'] ?? 'immagine' }}" title="{{ $topic['title'] ?? 'Image Title' }}">
                            </div>
                        </div>
                    </div>
                    <div class="it-griditem-text-wrapper">
                        <h3>{{ $topic['title'] ?? 'Topic' }}</h3>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
