@props(['data' => []])

{{-- Card Teaser - Bootstrap Italia Exact Replica --}}
@php
    $category = $data['category'] ?? 'Organi di governo';
    $title = $data['title'] ?? 'Titolo';
    $description = $data['description'] ?? 'Descrizione';
    $url = $data['url'] ?? '#';
    $image = $data['image'] ?? null;
    $hasImage = $image !== null;
@endphp

<div class="card card-teaser {{ $hasImage ? 'card-teaser-image card-flex' : 'no-after' }} rounded shadow-sm {{ $hasImage ? '' : 'mb-0' }} border border-light">
    @if($hasImage)
    <div class="card-image-wrapper with-read-more">
        <div class="card-body p-3 pb-5">
    @else
    <div class="card-body pb-5">
    @endif
    
    @if($category)
    <div class="category-top">
        <span class="title-xsmall-semi-bold fw-semibold">{{ $category }}</span>
    </div>
    @endif
    
    <h3 class="card-title text-paragraph-medium u-grey-light">{{ $title }}</h3>
    
    @if($description)
    <p class="text-paragraph-card u-grey-light m-0">{{ $description }}</p>
    @endif
    
    @if($hasImage)
        </div>
        <div class="card-image card-image-rounded pb-5">
            <img src="{{ $image }}" alt="{{ $title }}">
        </div>
        <a class="read-more" href="{{ $url }}">
            <span class="text">Vai alla pagina</span>
            <svg class="icon">
                <use href="#it-arrow-right"></use>
            </svg>
            <span class="sr-only">Vai alla pagina</span>
        </a>
    @else
        @if($url)
        <a class="read-more" href="{{ $url }}">
            <span class="text">Vai alla pagina</span>
            <svg class="icon">
                <use href="#it-arrow-right"></use>
            </svg>
            <span class="sr-only">Vai alla pagina</span>
        </a>
        @endif
    </div>
    @endif
</div>
