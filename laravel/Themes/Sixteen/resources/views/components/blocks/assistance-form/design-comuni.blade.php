@props(['data' => []])
{{--
    Richiesta assistenza, passo dati Design Comuni (riferimento sito/assistenza-01-dati.html): breadcrumb, hero con link di accesso,
    indice laterale con scroll-spy, modulo a sezioni, informativa privacy con consenso e pulsanti Indietro/Invia. Testi, sezioni e
    campi arrivano dal JSON (tests.assistenza-01-dati.json): `sections[].fields[].kind` = input | select | textarea, un nuovo tipo di
    campo = un nuovo `@case`. Gli spazi come nel riferimento dipendono dalla posizione (primo campo / prima select). Validazione e invio
    del riferimento (just-validate, `scripts.js`) non sono replicati: il modulo e' solo markup. Stili: resources/css/design-comuni/
    (scope body:has(page-content tests.*)). Scroll-spy: dcNavscroll, per selettore `[data-bs-navscroll]`. Contatti: partial condiviso.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-breadcrumbs" role="navigation">
                <nav class="breadcrumb-container" aria-label="{{ __('pub_theme::blocks_common.shared.breadcrumb.label') }}"><ol class="breadcrumb p-0" data-element="breadcrumb">
                    @foreach ($d('breadcrumb.items', []) as $crumb)
                        <li class="breadcrumb-item"><a href="{{ $crumb['url'] ?? '#' }}">{{ $crumb['label'] ?? '' }}</a><span class="separator">/</span></li>
                    @endforeach
                    <li class="breadcrumb-item active" aria-current="page">{{ $d('breadcrumb.current') }}</li>
                </ol></nav>
            </div>
            <p></p>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-hero">
                <section class="it-hero-wrapper bg-white align-items-start">
                    <div class="it-hero-text-wrapper pt-0 ps-0 pb-40">
                        <h1 class="text-black" data-element="page-name">{{ $d('title') }}</h1>
                        <div class="hero-text"><p>{{ $d('hero.intro') }}<br><br> {{ $d('hero.login.question') }} <a href="{{ $d('hero.login.url', '#') }}">{{ $d('hero.login.label') }}</a></p></div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row mt-lg-50">
        <div class="col-12 col-lg-3 d-lg-block mb-4 d-none">
            <div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-one">
                <nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="{{ $d('side_nav.aria_label') }}" data-bs-navscroll>
                    <div class="navbar-custom" id="navbarNavProgress">
                        <div class="menu-wrapper">
                            <div class="link-list-wrapper">
                                <div class="accordion">
                                    <div class="accordion-item">
                                        <span class="accordion-header" id="accordion-title-one">
                                            <button class="accordion-button pb-10 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-one" aria-expanded="true" aria-controls="collapse-one"> {{ $d('side_nav.title') }}
                                                <svg class="icon icon-xs right"><use href="{{ $icon('it-expand') }}"></use></svg></button>
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar it-navscroll-progressbar" role="progressbar" aria-label="{{ __('pub_theme::a11y.reading_progress') }}" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div id="collapse-one" class="accordion-collapse collapse show" role="region" aria-labelledby="accordion-title-one">
                                            <div class="accordion-body">
                                                <ul class="link-list" data-element="page-index">
                                                    @foreach ($d('side_nav.items', []) as $item)
                                                        <li class="nav-item"><a class="nav-link" href="#{{ $item['anchor'] ?? '' }}"><span>{{ $item['label'] ?? '' }}</span></a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="col-12 col-lg-8 offset-lg-1">
            <form class="steppers-content" aria-live="polite" id="{{ $d('form_id') }}">
                <div class="it-page-sections-container">
                    @foreach ($d('sections', []) as $section)
                        <section class="it-page-section" id="{{ $section['id'] ?? '' }}">
                            <div class="cmp-card mb-40">
                                <div class="card has-bkg-grey shadow-sm p-big">
                                    <div class="card-header border-0 p-0 mb-lg-20">
                                        <div class="d-flex"><h2 class="title-xxlarge mb-0">{{ $section['title'] ?? '' }}</h2></div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="form-wrapper bg-white p-4">
                                            @foreach ($section['fields'] ?? [] as $field)
                                                @switch($field['kind'] ?? '')
                                                    @case('input')
                                                        <div class="form-group cmp-input mb-0">
                                                            <label class="cmp-input__label" for="{{ $field['id'] ?? '' }}">{{ $field['label'] ?? '' }}</label>
                                                            <input type="{{ $field['type'] ?? 'text' }}" @class(['form-control', 'mt-4' => $loop->first]) id="{{ $field['id'] ?? '' }}" name="{{ $field['name'] ?? '' }}"{{ ($field['required'] ?? false) ? ' required' : '' }}>
                                                            <div class="d-flex"><span class="form-text cmp-input__text"> {{ $field['hint'] ?? '' }}</span></div>
                                                        </div>
                                                        @break
                                                    @case('select')
                                                        <div @class(['select-wrapper', 'mb-40 mt-4' => $loop->first, 'p-big' => ! $loop->first, 'bg-transparent p-0'])>
                                                            <label for="{{ $field['id'] ?? '' }}" class="">{{ $field['label'] ?? '' }}</label>
                                                            <select id="{{ $field['id'] ?? '' }}" class="bg-transparent form-control"{{ ($field['required'] ?? false) ? ' required' : '' }}>
                                                                <option selected="selected" value="">{{ $field['placeholder'] ?? '' }}</option>
                                                                @foreach ($field['options'] ?? [] as $option)
                                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="d-flex"><span class="form-text cmp-input__text"> {{ $field['hint'] ?? '' }}</span></div>
                                                        </div>
                                                        @break
                                                    @case('textarea')
                                                        <div class="cmp-text-area p-0 mt-40">
                                                            <div class="form-group">
                                                                <label for="{{ $field['id'] ?? '' }}" class="d-block">{{ $field['label'] ?? '' }}</label>
                                                                <textarea class="text-area form-control" id="{{ $field['id'] ?? '' }}" rows="{{ $field['rows'] ?? 2 }}"{{ ($field['required'] ?? false) ? ' required' : '' }}></textarea>
                                                                <span class="label">{{ $field['hint'] ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                        @break
                                                @endswitch
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endforeach
                    <div class="privacy-wrapper">
                        <p class="text-paragraph mb-3">{{ $d('privacy.text') }} <a href="{{ $d('privacy.link.url', '#') }}" class="t-primary">{{ $d('privacy.link.label') }}</a></p>
                        <div class="form-check mb-2">
                            <div class="checkbox-body d-flex align-items-center flex-wrap">
                                <input type="checkbox" id="{{ $d('privacy.checkbox.id') }}" name="{{ $d('privacy.checkbox.name') }}" value="{{ $d('privacy.checkbox.value') }}" required>
                                <label class="title-small-semi-bold pt-1 mb-0" for="{{ $d('privacy.checkbox.id') }}">{{ $d('privacy.checkbox.label') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cmp-nav-steps">
                    <nav class="steppers-nav" aria-label="{{ $d('nav.aria_label') }}">
                        <button type="button" class="btn btn-sm steppers-btn-prev p-0">
                            <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $icon('it-chevron-left') }}"></use></svg>
                            <span class="text-button-sm t-primary">{{ $d('nav.back') }}</span>
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm steppers-btn-confirm send" data-bs-validate="validate">
                            <span class="text-button-sm">{{ $d('nav.submit') }}</span>
                            <svg class="icon icon-white icon-sm"><use href="{{ $icon('it-chevron-right') }}"></use></svg>
                        </button>
                    </nav>
                    <div id="alert-message" class="alert alert-success cmp-disclaimer rounded d-none" role="alert">
                        <span class="d-inline-block text-uppercase cmp-disclaimer__message">{{ $d('nav.saved_message') }}</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
