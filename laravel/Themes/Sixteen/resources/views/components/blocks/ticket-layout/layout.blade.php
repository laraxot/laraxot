@props(['data' => []])

@php
    use Modules\Fixcity\ViewModels\TicketLayoutViewModel;
    $vm = app(TicketLayoutViewModel::class, ['data' => $data]);
    $ns = 'fixcity::ticket';
    $initialSelectedTypes = $vm->selectedTypes();
    $initialSelectedStatuses = $vm->selectedStatuses();
@endphp

{{-- Design Comuni reference: https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html --}}
<div class="container" id="main-container">

{{-- Alpine: sync desktop ↔ modale; app.js applica filtro mappa su filter-types-updated --}}
<div x-data="{
    dispatchMapFilters() {
        const selectedTypes = [];
        document.querySelectorAll('[data-filter-type]:checked').forEach(function (cb) {
            selectedTypes.push(cb.value);
        });
        const selectedStatuses = [];
        document.querySelectorAll('[data-filter-status]:checked').forEach(function (cb) {
            selectedStatuses.push(cb.value);
        });
        const mapEl = document.getElementById('block-map');
        const detail = { types: selectedTypes, statuses: selectedStatuses };
        if (mapEl) {
            mapEl.dispatchEvent(new CustomEvent('filters-changed', { detail }));
        } else {
            window.dispatchEvent(new CustomEvent('filters-changed', { detail }));
        }
        window.dispatchEvent(new CustomEvent('filter-types-updated'));
    },
    toggleType(type, checked) {
        document.querySelectorAll('[data-filter-type=&quot;' + type + '&quot;]').forEach(function (cb) {
            cb.checked = checked;
        });
        this.dispatchMapFilters();
    },
    toggleStatus(status, checked) {
        document.querySelectorAll('[data-filter-status=&quot;' + status + '&quot;]').forEach(function (cb) {
            cb.checked = checked;
        });
        this.dispatchMapFilters();
    },
}" @filter-type-changed.window="toggleType($event.detail.type, $event.detail.checked)" @filter-status-changed.window="toggleStatus($event.detail.status, $event.detail.checked)">

    <div class="row justify-content-center mb-md-40 mb-lg-80">
        @include('pub_theme::components.blocks.ticket.heading', ['data' => $data])
        <hr class="d-none d-lg-block mt-30 mb-2">
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-3 d-none d-lg-block" id="{{ $vm->filtersSectionId() }}" aria-label="{{ $vm->filtersTitle() }}">
            @if ($vm->hasSidebarFilters())
                @include('pub_theme::components.blocks.ticket.filters-sidebar', [
                    'filters' => $vm->filters(),
                    'statusFilters' => $vm->statusFilters(),
                    'selectedTypes' => $initialSelectedTypes,
                    'selectedStatuses' => $initialSelectedStatuses,
                    'context' => 'desktop',
                ])
            @else
                <p class="subtitle-small text-muted p-3">{{ __($ns . '.filters.empty') }}</p>
            @endif
        </div>

        <div class="col-lg-8 offset-lg-1">
            @include('pub_theme::components.blocks.ticket.results-header', [
                'ns' => $ns,
                'resultsCount' => $vm->resultsCount(),
                'sprite' => $vm->sprite(),
            ])

            <div class="tab-section">
                @include('pub_theme::components.blocks.ticket.tabs', [
                    'tabs' => $vm->tabs(),
                    'sectionId' => $vm->tabsId(),
                ])

                <div class="tab-content">
                    @php $cta = $vm->cta(); @endphp
                    <div
                        id="{{ $vm->defaultPanelId() }}"
                        role="tabpanel"
                        class="tab-pane fade show active"
                    >
                        <div class="row">
                            <div class="col-12">
                                <div class="map-box">
                                    @if ($vm->useReferenceStaticMap())
                                        <img
                                            src="{{ $vm->referenceMapImageUrl() }}"
                                            alt="{{ __($ns . '.map.image.alt') }}"
                                            class="w-100"
                                        >
                                    @else
                                        <map-lit
                                            id="block-map"
                                            class="w-100"
                                            legend-mode="sidebar"
                                            data-url="{{ $vm->mapDataUrl() }}"
                                            height="clamp(360px,58vh,560px)"
                                            style="height:clamp(360px,58vh,560px);display:block;width:100%"
                                            aria-label="{{ __($ns . '.map.image.alt') }}"
                                        ></map-lit>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (!empty($cta))
                            <div class="row mt-50 mb-4 mb-lg-0">
                                <div class="col-lg-6">
                                    <div class="cmp-text-button mt-0">
                                        <h2 class="title-xxlarge mb-0">{{ $cta['title'] }}</h2>
                                        <div class="text-wrapper">
                                            <p class="subtitle-small mb-3 mt-3">{{ $cta['text'] }}</p>
                                        </div>
                                        <div class="button-wrapper">
                                            <button
                                                type="button"
                                                class="btn btn-primary mobile-full py-3 mt-2 mb-4 mb-lg-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-disservizio"
                                            >
                                                <span>{{ $cta['button_text'] }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div
                        id="data-ex-disservizio2"
                        role="tabpanel"
                        class="tab-pane fade"
                    >
                        <div class="row">
                            @forelse ($vm->liveTickets() as $item)
                                @include('pub_theme::components.blocks.ticket.ticket-card', [
                                    'item' => $item,
                                    'loop' => $loop,
                                    'ns' => $ns,
                                    'sprite' => $vm->sprite(),
                                ])
                            @empty
                                <div class="col-12 text-center py-5">
                                    <p class="subtitle-small text-muted">{{ __($ns . '.results.empty') }}</p>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-outline-primary mobile-full py-3 mt-10 mx-auto">
                            <span>{{ __($ns . '.load-more.button.label') }}</span>
                        </button>
                        @if (!empty($cta))
                            <div class="row mt-50 mb-4 mb-lg-0">
                                <div class="col-lg-6">
                                    <div class="cmp-text-button mt-0">
                                        <h2 class="title-xxlarge mb-0">{{ $cta['title'] }}</h2>
                                        <div class="text-wrapper">
                                            <p class="subtitle-small mb-3 mt-3">{{ $cta['text'] }}</p>
                                        </div>
                                        <div class="button-wrapper">
                                            <button
                                                type="button"
                                                class="btn btn-primary mobile-full py-3 mt-2 mb-4 mb-lg-0"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal-disservizio"
                                            >
                                                <span>{{ $cta['button_text'] }}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>{{-- /x-data wrapper --}}

</div>{{-- /#main-container --}}

@include('pub_theme::components.blocks.feedback.rating', ['data' => $data['rating'] ?? []])

@include('pub_theme::components.blocks.ticket.contacts', [
    'contacts' => $vm->contacts(),
    'contactsId' => $vm->contactsId(),
    'sprite' => $vm->sprite(),
])

@include('pub_theme::components.blocks.ticket.modal-disservizio', ['sprite' => $vm->sprite()])

@if ($vm->hasSidebarFilters())
    <div class="modal fade d-lg-none" id="modal-categories" tabindex="-1" aria-labelledby="modal-categories-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h2 class="title-medium-semi-bold" id="modal-categories-title">{{ $vm->filtersTitle() }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('pub_theme::ui.close') }}"></button>
                </div>
                <div class="modal-body text-black">
                    @include('pub_theme::components.blocks.ticket.filters-sidebar', [
                        'filters' => $vm->filters(),
                        'statusFilters' => $vm->statusFilters(),
                        'selectedTypes' => $initialSelectedTypes,
                        'selectedStatuses' => $initialSelectedStatuses,
                        'context' => 'mobile',
                    ])
                </div>
            </div>
        </div>
    </div>
@endif

<style>.leaflet-container { z-index: 1; }</style>
