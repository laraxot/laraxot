{{-- Campo di ricerca in colonna centrata (pagina FAQ). $section = {col_class, search{...}}. --}}
<div class="row">
    <div class="{{ $section['col_class'] ?? 'col-12' }}">
        @include('pub_theme::components.blocks.content-page.partials.search', ['search' => $section['search'] ?? []])
    </div>
</div>
