{{-- Sezione di card documento. $section = {title, col_class, nested?, items[...]} (nested = doppio `row g-4`, come nel riferimento). --}}
<h2 class="title-xxlarge mb-4">{{ $section['title'] ?? '' }}</h2>
<div class="row g-4">
    @if (! empty($section['nested']))<div class="row g-4">@endif
    @foreach ($section['items'] ?? [] as $item)
        @include('pub_theme::components.blocks.content-page.partials.document-card', ['item' => $item, 'col' => $section['col_class'] ?? 'col-md-6 col-xl-4'])
    @endforeach
    @if (! empty($section['nested']))</div>@endif
</div>
