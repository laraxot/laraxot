@props(['data' => []])
{{--
    Passo di prenotazione appuntamento Design Comuni (pagine di riferimento appuntamento-01..05): breadcrumb, titolo, stepper, navigazione
    laterale con barra di avanzamento, sezioni e pulsanti. Testi, passi, opzioni e sezioni arrivano dal JSON della pagina
    (tests.appuntamento-*.json); le sezioni si scelgono per `type` (appointments, office-summary, select, textarea, form, applicant-card):
    un nuovo tipo = un nuovo `@case`. `layout = centered` = riepilogo finale senza navigazione laterale (partials/summary). Il passo 6
    (conferma) ha un blocco fratello: appointment-confirmation. Stili: resources/css/design-comuni/ (scope body:has(page-content tests.*)).
    Scroll-spy: dcNavscroll (resources/js/theme/design-comuni-home.js). Contatti: partial condiviso dc-contacts o partials/contacts-col.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $sections = $d('sections', []);
    $partials = 'pub_theme::components.blocks.appointment-step.partials';
@endphp
@include("$partials.breadcrumb")
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-hero">
                <section class="it-hero-wrapper bg-white align-items-start">
                    <div class="it-hero-text-wrapper pt-0 ps-0 pb-3 pb-lg-4">
                        <h1 class="text-black" data-element="page-name">{{ $d('title') }}</h1>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="steppers">
        <div class="steppers-header">
            <ul>
                @foreach ($d('steps.items', []) as $step)
                    <li class="{{ $step['state'] ?? '' }}"> {{ $step['label'] ?? '' }}
                        @if (($step['state'] ?? '') === 'confirmed')<svg class="icon steppers-success" aria-hidden="true"><use href="{{ $icon('it-check') }}"></use></svg><span class="visually-hidden">{{ $d('steps.confirmed_label') }}</span>@endif
                        @if (($step['state'] ?? '') === 'active')<span class="visually-hidden">{{ $d('steps.active_label') }}</span>@endif
                    </li>
                @endforeach
            </ul>
            <span class="steppers-index" aria-hidden="true">{{ $d('steps.index') }}</span>
        </div>
    </div>
    @if (filled($d('steps.required_notice')))
        <p class="title-xsmall d-lg-none my-5">{{ $d('steps.required_notice') }}</p>
    @endif
</div>
@if ($d('layout') === 'centered')
    @include("$partials.summary")
@else
<div class="container">
    <div class="row">
        @include("$partials.side-nav", ['col' => 'col-12 col-lg-3 d-lg-block mb-4 d-none'])
        <div class="col-12 col-lg-8 offset-lg-1">
            <div class="steppers-content" aria-live="polite">
                <div class="it-page-sections-container">
                    @foreach ($sections as $section)
                        <section class="it-page-section" id="{{ $section['id'] ?? '' }}">
                            <div @class(['cmp-card', 'mb-40' => ! $loop->last])>
                                <div class="card has-bkg-grey shadow-sm p-big">
                                    <div class="{{ trim('card-header border-0 p-0 mb-lg-20 '.($section['header_class'] ?? '')) }}">
                                        <div class="d-flex"><h2 class="title-xxlarge {{ $section['title_class'] ?? ($loop->last ? 'mb-0' : 'mb-2') }}">{{ $section['title'] ?? '' }}</h2></div>
                                        @if (filled($section['subtitle'] ?? null))
                                            <p class="subtitle-small {{ $section['subtitle_class'] ?? 'mb-0' }}">{{ $section['subtitle'] }}</p>
                                        @endif
                                    </div>
                                    <div class="card-body p-0">
                                        @switch($section['type'] ?? '')
                                            @case('appointments')
                                                <div class="select-wrapper p-0 mt-1 select-partials">
                                                    <label for="appointment" class="visually-hidden">{{ $section['select_label'] ?? '' }}</label>
                                                    <select id="appointment" class="">
                                                        <option selected="selected" value="">{{ $section['select_label'] ?? '' }}</option>
                                                        @foreach ($section['months'] ?? [] as $month)
                                                            <option value="{{ $month }}">{{ $month }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="cmp-card-radio-list mt-4">
                                                    <div class="card p-3">
                                                        <div class="card-body p-0">
                                                            <div class="form-check m-0">
                                                                <fieldset>
                                                                    <legend class="visually-hidden">{{ $section['legend'] ?? '' }}</legend>
                                                                    @foreach ($section['slots'] ?? [] as $slot)
                                                                        <div class="radio-body border-bottom border-light">
                                                                            <input name="radio" type="radio" id="radio-{{ $loop->iteration }}">
                                                                            <label for="radio-{{ $loop->iteration }}">{{ $slot }}</label>
                                                                        </div>
                                                                    @endforeach
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @break
                                            @case('office-summary')
                                                @include("$partials.info-summary", ['class' => 'bg-white mb-4 mb-lg-30 p-4', 'title' => $section['name'] ?? '', 'rows' => $section['rows'] ?? []])
                                                @break
                                            @case('select')
                                                @include("$partials.section-select")
                                                @break
                                            @case('textarea')
                                                @include("$partials.section-textarea")
                                                @break
                                            @case('form')
                                                @include("$partials.section-form")
                                                @break
                                            @case('applicant-card')
                                                @include("$partials.section-applicant")
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endforeach
                </div>
                @include("$partials.nav-steps")
            </div>
        </div>
    </div>
</div>
@endif
@if ($d('contacts.layout') === 'col')
    @include("$partials.contacts-col")
@else
    @include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
@endif
@if (filled($d('modal.id')))
    @include("$partials.modal-terms")
@endif
