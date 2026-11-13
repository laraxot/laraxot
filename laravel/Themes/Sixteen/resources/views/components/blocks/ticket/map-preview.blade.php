{{--
    Map Preview Block - Design Comuni compliant
    Uses civic design tokens: fc-map-section, fc-button, fc-green, fc-ink, fc-paper
    WCAG 2.1 AA compliant contrast ratios
--}}
@props(['data' => []])

@php
    $height   = $data['height'] ?? '420px';
    $dataUrl  = $data['data_url'] ?? '/api/tickets/geojson';
    // Fallback locali: il CMS può sovrascriverli, ma non deve introdurre URL di una lingua specifica.
    $reportUrl = $data['report_url'] ?? route('container0.view', ['container0' => 'tickets', 'slug0' => 'create']);
    $listUrl   = $data['list_url'] ?? route('container0.index', ['container0' => 'tickets']);
    $heading = (string) ($data['heading'] ?? __('fixcity::homepage.map_preview.section.title.label'));
    $intro = (string) ($data['intro'] ?? __('fixcity::homepage.map_preview.intro.text'));
    $eyebrow = (string) ($data['eyebrow'] ?? __('fixcity::homepage.map_preview.eyebrow.label'));
    $legend = (string) ($data['legend'] ?? __('fixcity::homepage.map_preview.legend.label'));
@endphp

<section class="fc-map-section" aria-labelledby="fc-map-heading">
    <div class="container">
        <div class="fc-section-heading">
            <div>
                <p class="fc-eyebrow">{{ $eyebrow }}</p>
                <h2 id="fc-map-heading">{{ $heading }}</h2>
            </div>
            <p>{{ $intro }}</p>
        </div>

        <div class="fc-map-shell">
            <map-lit
                class="d-block w-100"
                data-url="{{ $dataUrl }}"
                height="{{ $height }}"
                role="region"
                aria-label="{{ $eyebrow }}"
            ></map-lit>
            <div class="fc-map-shell__legend" aria-hidden="true">
                <span class="fc-map-shell__dot"></span>
                <span>{{ $legend }}</span>
            </div>
        </div>

        <div class="fc-map-actions" x-data>
            <a href="{{ $reportUrl }}" class="fc-button fc-button--primary">
                {{ __('fixcity::homepage.map_preview.cta_report.label') }}
            </a>
            <a href="{{ $listUrl }}" class="fc-button fc-button--outline">
                {{ __('fixcity::homepage.map_preview.cta_list.label') }}
            </a>
        </div>
    </div>
</section>
