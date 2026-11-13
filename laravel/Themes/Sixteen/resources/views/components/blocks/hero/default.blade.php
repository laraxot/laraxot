@props(['data' => []])

@php
    $eyebrow = $data['eyebrow'] ?? ($data['eyebrow_label'] ?? '');
    $title = $data['heading'] ?? ($data['title'] ?? ($data['page_title'] ?? ''));
    $intro = $data['intro'] ?? ($data['description'] ?? ($data['subtitle'] ?? ''));
    $primaryLabel = $data['cta_primary']['label'] ?? ($data['primary_label'] ?? '');
    $primaryUrl = $data['cta_primary']['url'] ?? ($data['primary_url'] ?? '#');
    $secondaryLabel = $data['cta_secondary']['label'] ?? ($data['secondary_label'] ?? '');
    $secondaryUrl = $data['cta_secondary']['url'] ?? ($data['secondary_url'] ?? '');
    $bgImage = $data['background_image'] ?? null;
@endphp

<div class="w-full bg-white dark:bg-slate-950 py-8 sm:py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @if($eyebrow)
            <p class="font-sans text-sm font-semibold uppercase tracking-wider text-sky-600 dark:text-sky-400">
                {{ $eyebrow }}
            </p>
        @endif

        @if($title)
            <h1 class="mt-4 font-bold text-4xl text-slate-900 dark:text-white sm:text-5xl">
                {{ $title }}
            </h1>
        @endif

        @if($intro)
            <p class="mt-6 max-w-3xl text-lg text-slate-600 dark:text-slate-400">
                {{ $intro }}
            </p>
        @endif

        @if($primaryLabel || $secondaryLabel)
            <div class="mt-8 flex flex-wrap gap-4">
                @if($primaryLabel)
                    <a href="{{ $primaryUrl }}"
                       class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                        {{ $primaryLabel }}
                    </a>
                @endif
                @if($secondaryLabel)
                    <a href="{{ $secondaryUrl }}"
                       class="inline-flex items-center justify-center rounded-lg border border-slate-300 dark:border-slate-600 bg-transparent px-6 py-3 text-base font-semibold text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                        {{ $secondaryLabel }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
