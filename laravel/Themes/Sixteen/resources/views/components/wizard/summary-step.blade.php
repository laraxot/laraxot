@props(['review' => [], 'data' => [], 'withContainer' => true, 'withShell' => true, 'withNav' => true, 'wired' => false])
{{--
    Passo "riepilogo" del wizard /tickets/create con il markup di sito/segnalazione-03-riepilogo (DCP-33).
    Dati: `review` = array di Modules\Fixcity\Actions\BuildTicketReviewSummaryAction (report, author, contacts: valori e titoli gia' tradotti);
    `data` = guscio con le stesse chiavi del JSON di tests.segnalazione-03-riepilogo (title, breadcrumb, steps, callout, nav: testi dal JSON o dai lang).
    Nessun testo qui dentro. Scelta di cosa stampare: `withContainer` (div.container#main-container), `withShell` (briciole, h1, barra dei passi)
    e `withNav` (Indietro / Salva / Invia): dentro un wizard il cui guscio ha gia' container, h1 e stepper si passa `:with-container="false" :with-shell="false"`
    e resta `row > col-lg-8` con avviso, schede e nav. `wired` aggiunge i soli ganci Alpine del wizard (Indietro = goToPreviousStep, Modifica = goToStep
    del passo dei dati); senza, il markup e' identico al riferimento. Pezzi: summary-shell, summary-callout, summary-sections, report-partials/steps-nav.
    I partial ereditano $d, $icon, $assets, $parts da qui (come report-step/design-comuni).
--}}
@php
    $wired && data_set($data, 'nav.back_click', 'goToPreviousStep', false);
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $parts = 'pub_theme::components.blocks.report-partials';
    $wizard = 'pub_theme::components.wizard';
@endphp
@if ($withContainer)<div class="container" id="main-container">@endif
    @if ($withShell)
        @include($wizard.'.summary-shell')
    @endif
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            @include($wizard.'.summary-callout')
            @include($wizard.'.summary-sections')
            @if ($withNav)
                @include($parts.'.steps-nav')
            @endif
        </div>
    </div>
@if ($withContainer)</div>@endif
