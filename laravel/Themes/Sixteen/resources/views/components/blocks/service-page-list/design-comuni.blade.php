@props(['data' => []])
{{--
    Pagina elenco servizi Design Comuni (riferimenti sito/servizi e sito/servizi-categoria): testata comune (breadcrumb + hero),
    poi le sezioni di `sections[]` scelte per `type` (service-search = fascia grigia con ricerca, card servizio, "carica altri" e
    colonna laterale; category-cards = card semplici di categorie o bandi), infine i partial condivisi rating e contatti.
    Testi e link arrivano dal JSON (tests.servizi.json, tests.servizi-categoria.json); un nuovo tipo di sezione = un nuovo `@case`.
    Stili: resources/css/design-comuni/ (scope body:has(page-content tests.*)).
--}}
@include('pub_theme::components.blocks.list-parts.breadcrumb', ['data' => $data])
@include('pub_theme::components.blocks.list-parts.hero', ['data' => $data])
@foreach (data_get($data, 'sections', []) as $section)
    @switch($section['type'] ?? '')
        @case('service-search')
            @include('pub_theme::components.blocks.service-page-list.service-search', ['section' => $section])
            @break
        @case('category-cards')
            @include('pub_theme::components.blocks.list-parts.category-cards', ['section' => $section])
            @break
    @endswitch
@endforeach
@include('pub_theme::components.blocks.partials.dc-rating', ['data' => $data])
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
