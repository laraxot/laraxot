@props(['data' => []])
{{--
    Passo del flusso "Segnalazione disservizio" Design Comuni (riferimenti sito/segnalazione-01-privacy ... 04-conferma). Un solo blocco per
    tutti i passi: `layout` sceglie la struttura della pagina (narrow = consenso, sidebar = modulo con indice laterale, summary = riepilogo,
    done = conferma) e `sections` (o `groups`, `heading`, `related`) porta i contenuti; un nuovo tipo di sezione = un nuovo `@case` nel
    layout. Testi, passi, campi, opzioni e link arrivano dal JSON della pagina (tests.segnalazione-0N-*.json). Stili: resources/css/design-comuni/
    (scope body:has(page-content tests.*)). Scroll-spy dell'indice: `dcNavscroll`; valutazione: `dcRating`; accordion e modale: app.js.
    Partial in ../report-partials (breadcrumb, stepper, navscroll, info-summary, steps-nav, rating, contacts); ereditano $d, $assets, $icon.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $parts = 'pub_theme::components.blocks.report-partials';
@endphp
@include('pub_theme::components.blocks.report-step.layout-'.$d('layout', 'narrow'))
@if (filled($d('feedback.title')))
    @include($parts.'.rating')
@endif
@include($parts.'.contacts')
@if (filled($d('modal.id')))
    @include('pub_theme::components.blocks.report-step.modal-terms')
@endif
