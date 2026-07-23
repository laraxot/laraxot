{{--
    Heading Block - Bootstrap Italia Style
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html
--}}
@props(['data' => []])

@php
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $description = $data['description'] ?? '';
    $variant = $data['variant'] ?? 'default'; // default, centered, with-divider
@endphp

<div class="cmp-heading p-0">
    <h1 class="title-xxxlarge">{{ $title }}</h1>
    
    @if($subtitle)
    <p class="subtitle-small">{{ $subtitle }}</p>
    @endif
    
    @if($description)
    <div class="mt-3">
        {!! $description !!}
    </div>
    @endif
</div>
