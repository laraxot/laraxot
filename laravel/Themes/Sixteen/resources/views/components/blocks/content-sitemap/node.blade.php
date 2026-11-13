{{-- Voce dell'albero della mappa del sito, ricorsiva. Parametri: $node = {label, url, emphasis?, sublist_id?, children[]}, $top = voce di primo livello. --}}
<li>
    <a class="{{ $top ? 'list-item large medium' : 'list-item' }}" href="{{ $node['url'] ?? '#' }}">
        @if ($top)
            <span class="list-item-title">{{ $node['label'] ?? '' }}</span>
        @else
            @if (! empty($node['emphasis']))
                <span class="fw-semibold">{{ $node['label'] ?? '' }}</span>
            @else
                <span>{{ $node['label'] ?? '' }}</span>
            @endif
        @endif
    </a>
    @if (! empty($node['children']))
        <ul class="link-sublist"@if (array_key_exists('sublist_id', $node)) id="{{ $node['sublist_id'] }}"@endif>
            @foreach ($node['children'] as $child)
                @include('pub_theme::components.blocks.content-sitemap.node', ['node' => $child, 'top' => false])
            @endforeach
        </ul>
    @endif
</li>
