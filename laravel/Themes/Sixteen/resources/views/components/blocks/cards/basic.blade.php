@props(['title' => null, 'href' => '#'])

<article {{ $attributes->class(['card', 'blocks-cards-basic']) }}>
    @if ($title)
        <h3 class="card-title">{{ $title }}</h3>
    @endif
    <div class="card-body">{{ $slot }}</div>
    <a href="{{ $href }}" class="stretched-link" aria-label="{{ $title }}"></a>
</article>
