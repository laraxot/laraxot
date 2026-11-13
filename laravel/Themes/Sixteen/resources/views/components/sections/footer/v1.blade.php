@props([
    'blocks' => [],
    'view' => null,
    'brand' => null,
    'columns' => [],
    'contact' => null,
    'legal_links' => [],
    'social' => [],
    'social_title' => null,
    'copyright' => null,
    'footer_bottom_links' => [],
    'eu_logo' => null,
])
{{--
    Footer Design Comuni (it-footer > it-footer-main > it-footer-small-prints), runtime owner unico (docs/bmad/header-template-naming.md).
    Riferimento: design-comuni-pagine-statiche/sito/homepage.html. Story: docs/stories/header-footer-design-comuni-markup.story.md.
    Dati: sections/footer.json via <x-section slug="footer" tpl="v1"> (brand, columns, contact.title/links, legal_links,
    footer_bottom_links, social_title, copyright, eu_logo). Recapiti e social dell'ente: UNICA sorgente GetEnteDataAction (DCP-289).
    Regole: solo markup + FrontofficeUrl; una voce senza URL sparisce (mai href="#"); un recapito vuoto sparisce, senza recapiti
    sparisce il titolo "Contatti", senza social sparisce "Seguici su". Niente copy istituzionale qui.
--}}
@php
    $configuredBlock = collect($blocks)->first();
    $configuredData = is_object($configuredBlock)
        ? ($configuredBlock->data ?? [])
        : (is_array($configuredBlock) ? ($configuredBlock['data'] ?? []) : []);

    if (is_array($configuredData) && $configuredData !== []) {
        $brand = $configuredData['brand'] ?? $brand;
        $columns = $configuredData['columns'] ?? $columns;
        $contact = $configuredData['contact'] ?? $contact;
        $legal_links = $configuredData['legal_links'] ?? $legal_links;
        $social = $configuredData['social'] ?? $social;
        $social_title = $configuredData['social_title'] ?? $social_title;
        $copyright = $configuredData['copyright'] ?? $copyright;
        $footer_bottom_links = $configuredData['footer_bottom_links'] ?? $footer_bottom_links;
        $eu_logo = $configuredData['eu_logo'] ?? $eu_logo;
    }

    $locale = app()->getLocale();
    $value = static function (mixed $value, string $fallback = '') use ($locale): string {
        if (is_array($value)) {
            return (string) ($value[$locale] ?? $value['it'] ?? reset($value) ?: $fallback);
        }

        return trim((string) $value) !== '' ? (string) $value : $fallback;
    };
    // path JSON senza lingua -> URL FO localizzato; esterni, tel: e mailto: passano invariati
    $localize = static fn (mixed $url): string => \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl(trim((string) $url));
    $hasUrl = static function (mixed $item): bool {
        if (! is_array($item)) {
            return false;
        }
        $url = trim((string) ($item['url'] ?? ''));

        return $url !== '' && $url !== '#';
    };
    $activeLinks = static fn (mixed $links): array => array_values(array_filter(is_array($links) ? $links : [], $hasUrl));
    $isExternal = static fn (string $url): bool => str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $lines = static fn (string $text): string => implode('<br>', array_map('e', preg_split('/\R/', $text) ?: []));

    $brand = is_array($brand) ? $brand : [];
    $brandLogo = trim((string) ($brand['logo'] ?? '')) !== '' ? (string) $brand['logo'] : 'assets/fixcity/svg/logo.svg';
    $brandName = $value($brand['name'] ?? null, 'FixCity');
    $eu_logo = is_array($eu_logo) ? $eu_logo : [];
    $euSrc = trim((string) ($eu_logo['src'] ?? ''));
    $contact = is_array($contact) ? $contact : [];
    $columnsWithLinks = [];
    foreach (is_array($columns) ? $columns : [] as $column) {
        $columnLinks = $activeLinks(is_array($column) ? ($column['links'] ?? []) : []);
        if ($columnLinks !== []) {
            $columnsWithLinks[] = ['title' => $value($column['title'] ?? null), 'links' => $columnLinks];
        }
    }
    $contactLinks = $activeLinks($contact['links'] ?? []);
    // data-element del riferimento Design Comuni sulle pagine legali, quando il JSON non lo dichiara
    $legalElements = ['/privacy' => 'privacy-policy-link', '/note-legali' => 'legal-notes', '/accessibilita' => 'accessibility-link'];
    $legalLinks = array_map(static function (array $link) use ($legalElements): array {
        $link['data_element'] = filled($link['data_element'] ?? null) ? $link['data_element'] : ($legalElements[rtrim((string) $link['url'], '/')] ?? null);

        return $link;
    }, $activeLinks($legal_links));
    $bottomLinks = $activeLinks($footer_bottom_links);

    // DCP-289: recapiti e social dell'ente, gia' validati; ogni campo vuoto non produce la sua riga
    $ente = app(\Modules\Tenant\Actions\Ente\GetEnteDataAction::class)->execute();
    // etichette da footer.json (contact.labels), gia' nella lingua della pagina: pub_theme::footer.* esiste solo in italiano
    $contactLabels = is_array($contact['labels'] ?? null) ? $contact['labels'] : [];
    $contactRows = [];
    foreach (['address', 'hours', 'phone', 'toll_free', 'email', 'pec', 'tax_code', 'vat', 'dpo_email'] as $field) {
        $link = $ente->link($field);
        $text = $link['label'] ?? (string) data_get($ente, $field, '');
        if ($text === '') {
            continue;
        }
        $label = $value($contactLabels[$field.'_label'] ?? null);
        if ($field === 'dpo_email' && $ente->dpo_name !== '') {
            $label = trim($label.' ('.$ente->dpo_name.')');
        }
        $contactRows[] = ['label' => $label, 'text' => $text, 'href' => $link['href'] ?? null];
    }
    // social: prima l'ente, poi eventuali voci del JSON con URL; nessun doppione
    $socialLinks = [];
    foreach ([...$ente->social, ...$activeLinks($social)] as $item) {
        $url = trim((string) ($item['url'] ?? ''));
        if ($url !== '' && ! isset($socialLinks[$url])) {
            $socialLinks[$url] = ['url' => $localize($url), 'label' => $value($item['label'] ?? null, $url), 'icon' => (string) ($item['icon'] ?? 'it-link')];
        }
    }
@endphp
<footer class="it-footer" id="footer">
    <div class="it-footer-main">
        <div class="container">
            <div class="row">
                <div class="col-12 footer-items-wrapper logo-wrapper">
                    @if ($euSrc !== '')
                        <img class="ue-logo" src="{{ \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::url($euSrc) }}" alt="{{ $value($eu_logo['alt'] ?? null) }}">
                    @endif
                    <div class="it-brand-wrapper">
                        {{-- The visible brand heading already provides the accessible name. --}}
                        <a href="{{ $localize('/') }}">
                            {{-- stesso marchio dell'header (schema brand.logo) --}}
                            @include('pub_theme::components.sections.partials.brand-mark', ['src' => $brandLogo, 'size' => 82, 'invert' => true])
                            <div class="it-brand-text">
                                <h2 class="no_toc">{{ $brandName }}</h2>
                                @if (filled($brand['description'] ?? null))
                                    <p class="h3">{{ $value($brand['description'] ?? null) }}</p>
                                @endif
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($columnsWithLinks as $column)
                    <div class="col-md-3 footer-items-wrapper">
                        @if ($column['title'] !== '')
                            <h4 class="footer-heading-title">{{ $column['title'] }}</h4>
                        @endif
                        <ul class="footer-list">
                            @foreach ($column['links'] as $link)
                                <li><a href="{{ $localize($link['url']) }}"@if (filled($link['data_element'] ?? null)) data-element="{{ $link['data_element'] }}"@endif>{{ $value($link['label'] ?? null) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
                @if ($contactRows !== [] || $contactLinks !== [] || $legalLinks !== [])
                    <div class="col-md-9 mt-md-4 footer-items-wrapper">
                        @if ($contactRows !== [] && filled($contact['title'] ?? null))
                            <h4 class="footer-heading-title">{{ $value($contact['title'] ?? null) }}</h4>
                        @endif
                        <div class="row">
                            @if ($contactRows !== [])
                                <div class="col-md-4">
                                    <p class="footer-info">
                                        @foreach ($contactRows as $row)
                                            @if (! $loop->first)<br>@endif
                                            @if ($row['label'] !== ''){{ $row['label'] }}: @endif
                                            @if ($row['href'] !== null)<a href="{{ $row['href'] }}">{{ $row['text'] }}</a>@else{!! $lines($row['text']) !!}@endif
                                        @endforeach
                                    </p>
                                </div>
                            @endif
                            @foreach ([$contactLinks, $legalLinks] as $list)
                                @if ($list !== [])
                                    <div class="col-md-4">
                                        <ul class="footer-list">
                                            @foreach ($list as $link)
                                                <li><a href="{{ $localize($link['url']) }}"@if (filled($link['data_element'] ?? null)) data-element="{{ $link['data_element'] }}"@endif>{{ $value($link['label'] ?? null) }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($socialLinks !== [])
                    <div class="col-md-3 mt-md-4 footer-items-wrapper">
                        <h4 class="footer-heading-title">{{ $value($social_title, (string) __('pub_theme::footer.follow_us')) }}</h4>
                        <ul class="list-inline text-start social">
                            @foreach ($socialLinks as $item)
                                <li class="list-inline-item">
                                    <a class="p-1 text-white" href="{{ $item['url'] }}"@if ($isExternal($item['url'])) target="_blank" rel="noopener noreferrer"@endif>
                                        <svg class="icon icon-sm icon-white align-top" aria-hidden="true"><use xlink:href="{{ $icon($item['icon']) }}"></use></svg>
                                        <span class="visually-hidden">{{ $item['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            @if ($bottomLinks !== [])
                <div class="row">
                    <div class="col-12 footer-items-wrapper">
                        <div class="footer-bottom">
                            @foreach ($bottomLinks as $link)
                                <a href="{{ $localize($link['url']) }}"@if (! empty($link['preferences'])) data-cookie-preferences data-element="cookie-preferences"@elseif (filled($link['data_element'] ?? null)) data-element="{{ $link['data_element'] }}"@endif>{{ $value($link['label'] ?? null) }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if (filled($copyright))
        <div class="it-footer-small-prints clearfix">
            <div class="container">
                <ul class="it-footer-small-prints-list list-inline mb-0 d-flex flex-column flex-md-row">
                    <li class="list-inline-item">{{ $value($copyright) }}</li>
                </ul>
            </div>
        </div>
    @endif
</footer>
