@props(['data' => []])
{{--
    Pagina legale dal testo dell'ente (DCP-130/131): informativa privacy (`policy.md`), note legali (`legal-notes.md`), media policy
    (`media-policy.md`) nel tenant, `config/<tenant>/lang/<lingua>/<file>`. Dati del JSON: `document` (privacy | legal_notes | media_policy,
    sceglie il titolo in lang/legal.php), `markdown` (nome del file), `assess` (`gdpr-art13` = verifica le sezioni dell'art. 13).
    Il codice non scrive nessun valore legale: senza testo, o con testo incompleto, la pagina risponde 200 e mostra un avviso che elenca
    che cosa il titolare deve ancora fornire. Markup: breadcrumb e hero di sito/novita-dettaglio, nota `callout` del riferimento.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $document = (string) $d('document');
    $title = __('pub_theme::legal.documents.'.$document.'.title');
    $built = app(\Modules\Fixcity\Actions\BuildLegalDocumentAction::class)->execute((string) $d('markdown'), (string) $d('assess'));
    $assessNs = $d('assess') === 'legal-notes' ? 'legal_notes' : 'privacy';
    $issues = $built['issues'];
    $html = $built['html'];
    $hasNotice = $issues !== [] || $html === null;
@endphp
<div class="container" id="main-container" data-slug="{{ $document === 'privacy' ? 'privacy.index' : $document.'.index' }}">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @include('pub_theme::components.blocks.content-page.partials.breadcrumb', ['breadcrumb' => ['items' => [['label' => __('pub_theme::legal.home'), 'url' => '/'.app()->getLocale()]], 'current' => $title]])
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-hero">
                <section class="it-hero-wrapper bg-white align-items-start">
                    <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                        <h1 class="text-black" data-element="page-name">{{ $title }}</h1>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="row">
                <div class="col-lg-9">
            @if ($hasNotice)
                <div class="callout callout-highlight ps-3 warning mb-5" data-element="legal-incomplete">
                    <div class="callout-title mb-20 d-flex align-items-center">
                        <svg class="icon icon-sm" aria-hidden="true"><use href="{{ $icon('it-horn') }}"></use></svg>
                        <h2 class="h5 mb-0">{{ __('pub_theme::legal.incomplete.title') }}</h2>
                    </div>
                    @if ($issues === [])
                        <p>{{ __('pub_theme::legal.incomplete.document_missing') }}</p>
                    @else
                        <p>{{ __($html === null ? 'pub_theme::legal.incomplete.intro_missing' : 'pub_theme::legal.incomplete.intro_present') }}</p>
                        <ul>
                            @foreach ($issues as $section => $reason)
                                <li><strong>{{ __('fixcity::'.$assessNs.'.sections.'.$section.'.title') }}</strong>: {{ __('fixcity::privacy.reason.'.$reason) }}. {{ __('fixcity::'.$assessNs.'.sections.'.$section.'.hint') }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
            @if ($html !== null)
                <div class="legal-text" data-element="legal-text">{!! $html !!}</div>
            @endif
                </div>
            </div>
        </div>
    </div>
</div>
