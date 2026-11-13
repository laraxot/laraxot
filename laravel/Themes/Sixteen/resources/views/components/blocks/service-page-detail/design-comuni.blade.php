@props(['data' => []])
{{--
    Dettaglio servizio Design Comuni (riferimento sito/servizio-dettaglio): breadcrumb, intestazione (titolo, stato, azioni a tendina),
    indice laterale con scroll-spy e sezioni scelte per `type` (content = blocchi di testo/liste/file/pulsanti, timeline = scadenze,
    contacts = ufficio e argomenti), poi valutazione e, in fascia grigia, contenuti correlati (carosello) e contatti. Testi, link e
    struttura delle sezioni arrivano dal JSON (tests.servizio-dettaglio.json); un nuovo tipo di sezione = un nuovo `@case`.
    Stili: resources/css/design-comuni/. Comportamenti: tendine, accordion dell'indice e carosello del tema (gia' montati per selettore).
--}}
@include('pub_theme::components.blocks.list-parts.breadcrumb', ['data' => $data])
@include('pub_theme::components.blocks.service-page-detail.heading', ['data' => $data])
<div class="container">
    <div class="row row-column-menu-left mt-4 mt-lg-80 pb-lg-80 pb-40">
        <div class="col-12 col-lg-3 mb-4 border-col">
            @include('pub_theme::components.blocks.service-page-detail.side-nav', ['nav' => data_get($data, 'side_nav', [])])
        </div>
        <div class="col-12 col-lg-8 offset-lg-1">
            <div class="it-page-sections-container">
                @foreach (data_get($data, 'sections', []) as $section)
                    @switch($section['type'] ?? '')
                        @case('content')
                            @include('pub_theme::components.blocks.service-page-detail.section-content', ['section' => $section])
                            @break
                        @case('timeline')
                            @include('pub_theme::components.blocks.service-page-detail.section-timeline', ['section' => $section])
                            @break
                        @case('contacts')
                            @include('pub_theme::components.blocks.service-page-detail.section-contacts', ['section' => $section])
                            @break
                    @endswitch
                @endforeach
            </div>
        </div>
    </div>
</div>
{{-- ponytail: il riferimento stringe la colonna del rating (`p-lg-0 px-4`); finche' dc-rating non accetta l'opzione si ritocca l'HTML reso. --}}
@include('pub_theme::components.blocks.partials.dc-rating', ['data' => array_replace_recursive($data, ['feedback' => ['col_class' => 'p-lg-0 px-4']])])
@include('pub_theme::components.blocks.service-page-detail.related-contacts', ['data' => $data])
