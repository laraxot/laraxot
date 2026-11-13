@props(['data' => [], 'query' => '', 'results' => [], 'result_count' => null, 'filters' => [], 'filters_typology' => [], 'filters_topics' => [], 'rating' => [], 'contacts' => []])

@php
    $payload = is_array($data) ? $data : [];
    $query = (string) ($payload['query'] ?? $query);
    $resultData = is_array($payload['results'] ?? null) ? $payload['results'] : [];
    $results = is_array($resultData['items'] ?? null) ? $resultData['items'] : $results;
    $count = (int) ($resultData['count'] ?? $payload['result_count'] ?? $payload['count'] ?? $result_count ?? 645);
    $filterData = is_array($payload['filters'] ?? null) ? $payload['filters'] : [];
    $groups = is_array($filterData['groups'] ?? null) ? array_map(static fn (array $group): array => [
        'label' => $group['title'] ?? '',
        'options' => $group['items'] ?? [],
    ], $filterData['groups']) : array_values(array_filter([$filters_typology, $filters_topics], static fn ($group) => is_array($group) && $group !== []));
    $rating = is_array($payload['feedback'] ?? null) ? $payload['feedback'] : $rating;
    $contacts = is_array($payload['contacts'] ?? null) ? $payload['contacts'] : $contacts;
    $label = (string) ($payload['search']['label'] ?? $payload['search_label'] ?? __('pub_theme::components_search.demo.with_label_lower.label'));
    $searchButton = (string) ($payload['search']['button'] ?? $payload['search_button'] ?? __('pub_theme::header.center.search.label'));
    $loadMore = (string) ($resultData['load_more'] ?? $payload['load_more_label'] ?? __('pub_theme::components_common.actions.load_more_results.label'));
    $filtersLabel = (string) ($resultData['filter_button'] ?? $payload['filters_label'] ?? __('pub_theme::components_reports.filters.filter.label'));
    $clearLabel = (string) ($resultData['clear'] ?? $payload['clear_filters_label'] ?? __('pub_theme::components_reports.filters.remove_all.label'));
    $resultLabel = (string) ($resultData['count_label'] ?? $payload['result_label'] ?? __('pub_theme::components_common.search.results_count.label'));
    $homeUrl = (string) ($payload['home_url'] ?? route('container0.index', ['container0' => 'tests']));
    $ratingTitle = (string) ($rating['title'] ?? __('pub_theme::news.index.feedback.title'));
    $ratingThanks = (string) ($rating['thanks'] ?? __('pub_theme::news.index.feedback.thanks'));
    $contactTitle = (string) data_get($contacts, 'main.title', __('pub_theme::navigation.homepage.contacts'));
@endphp

<div id="search-results-page" class="search-results-page bg-white text-[#17324d]" x-data="{filtersOpen:false, selected:[]}" @keydown.escape.window="filtersOpen=false">
    <h1 id="search-container" class="sr-only">{{ __('pub_theme::components_search.page.title.label') }}</h1>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <nav class="py-5 text-base" aria-label="{{ __('pub_theme::components_common.a11y.breadcrumb_trail.label') }}">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a class="text-[#007a52] no-underline hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-[#007a52]" href="{{ $homeUrl }}">{{ __('pub_theme::components_common.nav.home.label') }}</a><span class="ml-2 text-[#5c6f82]" aria-hidden="true">/</span></li>
                <li aria-current="page" class="text-[#5c6f82]">{{ __('pub_theme::components_search.page.title.label') }}</li>
            </ol>
        </nav>
    </div>

    <form class="dc-search-form mx-auto flex max-w-7xl flex-col gap-3 px-4 pb-10 pt-2 sm:px-6 lg:flex-row lg:items-end lg:px-8" role="search" action="{{ $payload['search_action'] ?? request()->url() }}" method="get">
        <div class="min-w-0 flex-1">
            <label for="input-group-ricerca" class="mb-2 block text-sm font-semibold text-[#17324d]">{{ $label }}</label>
            <div class="flex rounded border border-[#5c6f82] bg-white shadow-sm focus-within:ring-2 focus-within:ring-[#007a52]">
                <span class="flex items-center px-4 text-[#007a52]" aria-hidden="true"><x-ui.icon name="it-search" class="h-5 w-5" /></span>
                <input id="input-group-ricerca" name="q" type="search" value="{{ $query !== '' ? $query : ($payload['search']['query'] ?? '') }}" placeholder="{{ $payload['search']['placeholder'] ?? 'Pagamento TARI' }}" class="min-h-12 min-w-0 flex-1 border-0 px-2 py-3 text-[#17324d] outline-none focus:ring-0" />
            </div>
        </div>
        <button type="submit" class="inline-flex min-h-12 items-center justify-center rounded bg-[#007a52] px-8 py-3 font-semibold text-white shadow-sm transition hover:bg-[#006341] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#007a52]/30">{{ $searchButton }}</button>
    </form>

    <div class="dc-search-layout mx-auto grid max-w-7xl gap-8 px-4 pb-12 sm:px-6 lg:grid-cols-[minmax(0,3fr)_minmax(0,8fr)] lg:px-8">
        <aside class="dc-search-filters hidden lg:block" aria-labelledby="filter">
            <h2 id="filter" class="sr-only">{{ __('pub_theme::components_search.filters.to_apply_title.label') }}</h2>
            @foreach ($groups as $groupIndex => $group)
                @php $groupLabel = (string) ($group['label'] ?? ($groupIndex === 0 ? __('pub_theme::components_common.fields.types.label') : __('pub_theme::components_search.filters.topics.label'))); $options = is_array($group['options'] ?? null) ? $group['options'] : (array) $group; @endphp
                <fieldset class="mb-8">
                    <legend class="mb-3 text-sm font-bold uppercase tracking-wide text-[#17324d]">{{ $groupLabel }}</legend>
                    <ul class="space-y-1">
                        @foreach ($options as $optionIndex => $option)
                            @php $option = is_array($option) ? $option : ['id' => \Illuminate\Support\Str::slug((string) $option), 'label' => $option, 'count' => null]; $optionId = 'filter-'.$groupIndex.'-'.$optionIndex.'-'.($option['id'] ?? 'option'); @endphp
                            <li class="border-b border-[#d9e2e8] py-2">
                                <label for="{{ $optionId }}" class="flex cursor-pointer items-center gap-3 text-sm text-[#17324d]">
                                    <input id="{{ $optionId }}" type="checkbox" value="{{ $option['id'] ?? '' }}" x-model="selected" class="h-5 w-5 rounded border-[#5c6f82] accent-[#007a52] focus:ring-2 focus:ring-[#007a52]" />
                                    <span>{{ $option['label'] ?? $option['name'] ?? '' }}@if (isset($option['count'])) ({{ $option['count'] }}) @endif</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </fieldset>
            @endforeach
        </aside>

        <section class="dc-search-content" aria-labelledby="search-result">
            <div class="flex items-center justify-between border-b border-[#d9e2e8] pb-3">
                <h2 id="search-result" class="sr-only">{{ __('pub_theme::components_search.results.title.label') }}</h2>
                <span class="text-base font-semibold text-[#5c6f82]"><span>{{ $count }}</span> {{ $resultLabel }}</span>
                <button type="button" class="inline-flex min-h-11 items-center gap-2 rounded px-2 py-2 font-semibold text-[#007a52] lg:hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-[#007a52]" @click="filtersOpen=true" aria-controls="mobile-filters" :aria-expanded="filtersOpen.toString()"><x-ui.icon name="it-funnel" class="h-5 w-5" />{{ $filtersLabel }}</button>
                <button type="button" class="hidden rounded px-3 py-2 text-sm font-semibold text-[#007a52] disabled:cursor-not-allowed disabled:opacity-60 lg:block" disabled>{{ $clearLabel }}</button>
            </div>
            <div class="space-y-4 pt-4">
                @forelse ($results as $result)
                    @php $url = (string) ($result['url'] ?? '#'); $category = strtoupper((string) ($result['category'] ?? $result['type'] ?? __('pub_theme::header.center.nav.servizi.label'))); @endphp
                    <article class="dc-search-card rounded border border-[#d9e2e8] bg-white px-5 py-5 shadow-sm transition hover:shadow-md">
                        <span class="sr-only">{{ __('pub_theme::components_common.fields.category_colon.label') }} </span><a href="{{ $result['category_url'] ?? '#' }}" class="text-xs font-bold uppercase tracking-wide text-[#5c6f82] no-underline hover:underline">{{ $category }}</a>
                        <h3 class="mt-2 text-2xl font-bold leading-tight text-[#007a52]"><a href="{{ $url }}" class="no-underline hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-[#007a52]">{{ $result['title'] ?? '' }}</a></h3>
                        <p class="mt-2 text-base leading-relaxed text-[#17324d]">{{ strip_tags((string) ($result['description'] ?? $result['excerpt'] ?? '')) }}</p>
                    </article>
                @empty
                    <p class="rounded border border-[#d9e2e8] bg-[#f5f7f9] p-5">{{ $payload['empty_label'] ?? __('pub_theme::components_search.results.empty.message') }}</p>
                @endforelse
            </div>
            @if ($results !== [])
                <div class="flex justify-center py-10"><button type="button" class="min-h-12 rounded border-2 border-[#007a52] px-8 py-3 font-semibold text-[#007a52] transition hover:bg-[#007a52] hover:text-white focus:outline-none focus-visible:ring-4 focus-visible:ring-[#007a52]/30">{{ $loadMore }}</button></div>
            @endif
        </section>
    </div>

    <div x-cloak x-show="filtersOpen" x-transition.opacity class="fixed inset-0 z-50 bg-black/40 lg:hidden" role="dialog" aria-modal="true" aria-labelledby="mobile-filters-title" @click.self="filtersOpen=false">
        <div id="mobile-filters" class="absolute inset-x-0 bottom-0 max-h-[85vh] overflow-y-auto rounded-t-2xl bg-white p-6 text-[#17324d]" x-show="filtersOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0">
            <div class="flex items-center justify-between"><h2 id="mobile-filters-title" class="text-xl font-bold">{{ __('pub_theme::components_search.filters.by_category.label') }}</h2><button type="button" class="rounded p-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#007a52]" aria-label="{{ __('pub_theme::components_reports.filters.close.label') }}" @click="filtersOpen=false"><x-ui.icon name="it-close" class="h-6 w-6" /></button></div>
            <div class="mt-5">@foreach ($groups as $groupIndex => $group)<fieldset class="mb-6"><legend class="mb-3 text-sm font-bold uppercase">{{ $group['label'] ?? ($groupIndex === 0 ? __('pub_theme::components_common.fields.types.label') : __('pub_theme::components_search.filters.topics.label')) }}</legend><div class="space-y-2">@foreach (is_array($group['options'] ?? null) ? $group['options'] : (array) $group as $optionIndex => $option) @php $option = is_array($option) ? $option : ['id' => \Illuminate\Support\Str::slug((string) $option), 'label' => $option]; $optionId = 'mobile-filter-'.$groupIndex.'-'.$optionIndex; @endphp<label for="{{ $optionId }}" class="flex items-center gap-3 border-b border-[#d9e2e8] py-2"><input id="{{ $optionId }}" type="checkbox" value="{{ $option['id'] ?? '' }}" x-model="selected" class="h-5 w-5 accent-[#007a52]" /><span>{{ $option['label'] ?? $option['name'] ?? '' }}@if (isset($option['count'])) ({{ $option['count'] }}) @endif</span></label>@endforeach</div></fieldset>@endforeach</div>
            <button type="button" class="w-full rounded bg-[#007a52] px-5 py-3 font-semibold text-white focus:outline-none focus-visible:ring-4 focus-visible:ring-[#007a52]/30" @click="filtersOpen=false">{{ __('pub_theme::components_common.actions.apply.label') }}</button>
        </div>
    </div>

    @include('pub_theme::components.blocks.partials.dc-rating', ['data' => ['feedback' => $rating]])
    @include('pub_theme::components.blocks.partials.dc-contacts', ['data' => ['contacts' => $contacts]])
</div>
