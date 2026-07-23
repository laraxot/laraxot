@props([
    'title' => null,
    'subtitle' => null,
    'href' => '#',
    'hover' => false,
    'clickable' => false,
])

<article {{ $attributes->class(['card', 'blocks-cards-italia']) }}>
    @if ($title)
        <h3 class="card-title">{{ $title }}</h3>
    @endif
    @if ($subtitle)
        <p class="card-subtitle">{{ $subtitle }}</p>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
    @if ($clickable && $href)
        <a href="{{ $href }}" class="stretched-link" aria-label="{{ $title }}"></a>
    @endif
</article>
