@props(['data' => []])
{{--
    Pagina di accesso Design Comuni (riferimento servizi/accesso-servizio.html): breadcrumb, titolo, metodi di accesso (SPID, CIE,
    altre utenze) con pulsanti e link, e contatti (variante `offset` del partial). Testi, pulsanti e immagini dal JSON della pagina
    (tests.accesso-servizio.json): `methods[].layout` = single (un pulsante) o row (piu' pulsanti affiancati); `buttons[].variant` =
    icon (pulsante pieno con immagine) o outline. Le immagini sono in public/design-comuni/assets/images. Stili: resources/css/design-comuni/.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
@endphp
<div class="container" id="main-container">
    <div class="row">
        <div class="col-12 col-lg-10 offset-lg-1">
            <div class="cmp-breadcrumbs" role="navigation">
                <nav class="breadcrumb-container" aria-label="{{ __('pub_theme::blocks_common.shared.breadcrumb.label') }}"><ol class="breadcrumb p-0" data-element="breadcrumb">
                    @foreach ($d('breadcrumb.items', []) as $crumb)
                        <li class="breadcrumb-item"><a href="{{ $crumb['url'] ?? '#' }}">{{ $crumb['label'] ?? '' }}</a><span class="separator">/</span></li>
                    @endforeach
                    <li class="breadcrumb-item active" aria-current="page">{{ $d('breadcrumb.current') }}</li>
                </ol></nav>
            </div>
            <div class="cmp-heading pb-3 pb-lg-4">
                <h1 class="title-xxxlarge">{{ $d('title') }}</h1>
                <p class="subtitle-small">{{ $d('subtitle') }}</p>
            </div>
        </div>
    </div><hr class="d-none d-lg-block mt-0 mb-4">
    <div class="row">
        <div class="col-12 col-lg-8 offset-lg-2">
            @foreach ($d('methods', []) as $method)
                <div @class(['cmp-text-button', 'mt-3' => $loop->first])>
                    <h2 class="title-xxlarge mb-0">{{ $method['title'] ?? '' }}</h2>
                    <div class="text-wrapper"><p class="subtitle-small mb-3">{{ $method['text'] ?? '' }}</p></div>
                    <div @class(['button-wrapper', 'mb-2' => ($method['layout'] ?? 'single') === 'single', 'd-md-flex' => ($method['layout'] ?? '') === 'row'])>
                        @foreach ($method['buttons'] ?? [] as $button)
                            @if (($button['variant'] ?? '') === 'icon')
                                <button type="button" class="btn btn-icon btn-re square">
                                    <img src="{{ $assets }}/images/{{ $button['image']['file'] ?? '' }}" alt="{{ $button['image']['alt'] ?? '' }}" class="me-2">
                                    <span class="">{{ $button['label'] ?? '' }}</span></button>
                            @else
                                <button type="button" @class(['btn btn-outline-primary btn-re', 'pr-md-4' => $loop->first && $loop->count > 1, 'bg-white'])>
                                    <span class="">{{ $button['label'] ?? '' }}</span></button>
                            @endif
                        @endforeach
                    </div>
                    @if (filled($method['link'] ?? null))
                        <a class="simple-link" href="{{ $method['link']['url'] ?? '#' }}">{{ $method['link']['label'] ?? '' }} <span class="visually-hidden">{{ $method['link']['label'] ?? '' }}</span></a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
