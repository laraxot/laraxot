@props(['cardClass' => '', 'required' => false, 'last' => null])
{{--
    Scheda-sezione del passo "dati" del wizard /tickets/create (sito/segnalazione-02-dati, sezioni report-place / report-info / report-author, DCP-35 P2-A).
    Uso: `Section::make(heading)->description(...)->id('report-place')->view('pub_theme::components.wizard.dc-section-card', ['cardClass' => 'p-big p-lg-4'])->schema([...campi...])`.
    Filament rende il Blade al posto dell'HTML incorporato di Section (ViewComponent::view) e gli passa le closure pubbliche del componente:
    `$getHeading()`, `$getDescription()`, `$getId()`, `$getChildSchema()`; i dati di `view()` arrivano come variabili (@props).
    `cardClass` = modificatori della card (Luogo: `p-big p-lg-4`, Disservizio: `p-big`, Autore: vuoto); `required` = titolo con `icon-required`;
    `last` (opzionale) = ultima scheda (senza `mb-40`): se omesso e' automatico (ultima Section VISIBILE del contenitore, quindi corretto anche quando l'Autore e' nascosto all'ospite). Stesso markup di report-step/layout-sidebar (righe scheda): il CSS Design Comuni lo conosce gia'.
--}}
@php
    $description = $getDescription();
    $siblings = $schemaComponent->getContainer()->getComponents();
    $isLast = $last ?? (end($siblings) === $schemaComponent);
@endphp
<section class="it-page-section" id="{{ $getId() }}">
    <div @class(['cmp-card', 'mb-40' => ! $isLast])>
        <div @class(['card has-bkg-grey shadow-sm', $cardClass => filled($cardClass)])>
            <div class="card-header border-0 p-0 mb-lg-20 m-0">
                <div class="d-flex"><h2 @class(['title-xxlarge', 'mb-1' => ! $required, 'mb-3 icon-required' => $required])>{{ $getHeading() }}</h2></div>
                @if (filled($description))<p class="subtitle-small mb-0">{{ $description }}</p>@endif
            </div>
            <div class="card-body p-0">
                {{-- Il bundle ha regole GLOBALI per i wrapper Filament: `.fi-section-content` (sfondo bianco, padding 1.5rem), `.fi-sc-has-gap` (gap 24px) e `.fi-grid` (display:grid:
                     i margini dei figli non collassano piu', +28px prima della navigazione). Qui spenti: classe sostitutiva `d-block` (utility DC, c'e' nello scope). --}}
                {{ $getChildSchema()->gap(false)->extraAttributes(['class' => 'd-block']) }}
            </div>
        </div>
    </div>
</section>
