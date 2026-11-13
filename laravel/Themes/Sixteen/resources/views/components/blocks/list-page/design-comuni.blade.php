@props(['data' => []])
{{--
    Pagina lista Design Comuni (riferimenti sito/lista-categorie, lista-risorse-categorie, lista-risorse): testata comune
    (breadcrumb + hero), poi le sezioni di `sections[]` scelte per `type` (news-cards = card con immagine opzionale, ricerca e
    paginazione; category-cards = card semplici), infine i partial condivisi rating e contatti. Testi, link e immagini arrivano
    dal JSON (tests.lista-*.json); un nuovo tipo di sezione = un nuovo `@case`. Immagini in public/design-comuni/assets/images.
    Stili: resources/css/design-comuni/ (scope body:has(page-content tests.*)).
--}}
@include('pub_theme::components.blocks.list-parts.breadcrumb', ['data' => $data])
@include('pub_theme::components.blocks.list-parts.hero', ['data' => $data])
@foreach (data_get($data, 'sections', []) as $section)
    @switch($section['type'] ?? '')
        @case('news-cards')
            @include('pub_theme::components.blocks.list-page.news-cards', ['section' => $section])
            @break
        @case('category-cards')
            @include('pub_theme::components.blocks.list-parts.category-cards', ['section' => $section])
            @break
    @endswitch
@endforeach
@include('pub_theme::components.blocks.partials.dc-rating', ['data' => $data])
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
