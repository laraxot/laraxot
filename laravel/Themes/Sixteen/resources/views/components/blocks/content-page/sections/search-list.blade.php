{{-- Ricerca con elenco di card documento. $section = {title, col_class, search{...}, items[...], none}. `none` = riga finale "nessun altro risultato". --}}
<h2 class="title-xxlarge mb-4">{{ $section['title'] ?? '' }}</h2>
@include('pub_theme::components.blocks.content-page.partials.search', ['search' => $section['search'] ?? []])
<div class="row g-4">
    @foreach ($section['items'] ?? [] as $item)
        @include('pub_theme::components.blocks.content-page.partials.document-card', ['item' => $item, 'col' => $section['col_class'] ?? 'col-md-6 col-xl-4'])
    @endforeach
</div>
@if (filled($section['none'] ?? null))
    <p class="text-center text-paragraph-regular-medium mt-4 mb-0">{{ $section['none'] }}</p>
@endif
