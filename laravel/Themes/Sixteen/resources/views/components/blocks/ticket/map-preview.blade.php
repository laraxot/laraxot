@props(['data' => []])

@php
    $height   = $data['height'] ?? '350px';
    $dataUrl  = $data['data_url'] ?? '/data/tickets.json';
    $reportUrl = $data['report_url'] ?? '/it/tickets/create';
    $listUrl   = $data['list_url'] ?? '/it';
    $locale    = app()->getLocale();
    $ctaUrl   = auth()->check()
        ? $reportUrl
        : url("$locale/auth/login") . '?redirect=' . urlencode($reportUrl);
@endphp

<section class="homepage-map-preview py-8">
    <div class="container">
        <map-lit
            class="block w-full rounded-lg overflow-hidden shadow"
            data-url="{{ $dataUrl }}"
            height="{{ $height }}"
        ></map-lit>

        <div class="mt-4 flex flex-wrap gap-3 items-center" x-data>
            <a href="{{ $ctaUrl }}"
               class="btn btn-primary">
                {{ __('fixcity::homepage.map_preview.cta_report.label') }}
            </a>
            <a href="{{ $listUrl }}"
               class="btn btn-outline-primary">
                {{ __('fixcity::homepage.map_preview.cta_list.label') }}
            </a>
        </div>
    </div>
</section>
