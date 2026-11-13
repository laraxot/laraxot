@props(['blocks' => []])
{{--
    Modale di ricerca Design Comuni: markup del riferimento, dati da sections/search-modal.json. Apertura/chiusura, focus e trap: resources/js/app.js
    e resources/js/theme/modal-focus.js.
    DCP-288: la ricerca invia `q` (GET) alla ricerca globale /{lang}/ricerca (CMS ricerca.index.json, SearchFrontOfficeAction: segnalazioni
    pubbliche, notizie, eventi e pagine). Story: Modules/Fixcity/docs/stories/site-wide-front-office-search.story.md. Prima il campo si
    chiamava `autocomplete-two`, il form non aveva `action` e "Cerca" era `type="button"`.
--}}
@php
    $localizeSuggestionUrl = static function (mixed $url): string {
        if (! is_string($url) || $url === '' || str_starts_with($url, '#') || str_contains($url, '://')) {
            return is_string($url) && $url !== '' ? $url : '#';
        }

        return \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl($url);
    };
    $data = [];
    foreach ($blocks as $block) {
        $blockData = is_array($block) ? ($block['data'] ?? []) : ($block->data ?? []);
        if (is_array($blockData)) {
            $data = array_merge($data, $blockData);
        }
    }
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $currentQuery = request()->query('q');
    $currentQuery = is_string($currentQuery) ? $currentQuery : '';
    $searchUrl = \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::searchIndex();
    $icon = static fn (string $name): string => \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp
<div class="modal fade search-modal" id="search-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content perfect-scrollbar">
            <div class="modal-body">
                <form method="get" action="{{ $searchUrl }}" role="search">
                    <div class="container">
                        <div class="row variable-gutters">
                            <div class="col">
                                <div class="modal-title">
                                    <button class="search-link d-md-none" type="button" data-bs-toggle="modal" data-bs-target="#search-modal" aria-label="{{ $d('back_label') }}"><svg class="icon icon-md"><use href="{{ $icon('it-arrow-left') }}"></use></svg></button>
                                    <h2>{{ $d('title') }}</h2>
                                    <button class="search-link d-none d-md-block" type="button" data-bs-toggle="modal" data-bs-target="#search-modal" aria-label="{{ $d('back_label') }}"><svg class="icon icon-md"><use href="{{ $icon('it-close-big') }}"></use></svg></button>
                                </div>
                                <div class="form-group autocomplete-wrapper">
                                    <label for="autocomplete-two" class="visually-hidden">{{ $d('label') }}</label>
                                    <input type="search" class="autocomplete ps-5" placeholder="{{ $d('placeholder') }}" id="autocomplete-two" name="q" value="{{ $currentQuery }}" data-bs-autocomplete="[]">
                                    <span class="autocomplete-icon" aria-hidden="true"><svg class="icon"><use href="{{ $icon('it-search') }}"></use></svg></span>
                                    <button type="submit" class="btn btn-primary"><span class="">{{ $d('button') }}</span></button>
                                </div>
                            </div>
                        </div>
                        <div class="row variable-gutters">
                            <div class="col-lg-5">
                                <div class="searches-list-wrapper">
                                    <div class="other-link-title">{{ $d('suggestions_title') }}</div>
                                    <ul class="searches-list">
                                        @foreach ($d('suggestions', []) as $suggestion)
                                            <li><a href="{{ $localizeSuggestionUrl($suggestion['url'] ?? null) }}">{{ $suggestion['label'] ?? '' }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
