@props([
    'data' => [],
    'filters' => [],
    'map' => [],
    'tabs' => [],
    'cta' => [],
])

@php
    use Modules\Fixcity\ViewModels\TicketLayoutViewModel;

    $ns = 'fixcity::ticket';
    $locale = app()->getLocale();
    $localePrefix = '/' . $locale;

    $blockData = is_array($data) ? $data : [];
    $filters = is_array($filters) && $filters !== [] ? $filters : (is_array($blockData['filters'] ?? null) ? $blockData['filters'] : []);
    $map = is_array($map) && $map !== [] ? $map : (is_array($blockData['map'] ?? null) ? $blockData['map'] : ['data_url' => asset('data/tickets.json')]);
    $tabs = is_array($tabs) && ($tabs['items'] ?? []) !== [] ? $tabs : (is_array($blockData['tabs'] ?? null) ? $blockData['tabs'] : []);
    $cta = is_array($cta) && $cta !== [] ? $cta : (is_array($blockData['cta'] ?? null) ? $blockData['cta'] : []);

    $tabItems = is_array($tabs['items'] ?? null) ? $tabs['items'] : [];
    if ($tabItems === []) {
        $tabItems = [
            ['id' => 'map', 'label' => $ns . '.tabs.map.label'],
            ['id' => 'list', 'label' => $ns . '.tabs.list.label'],
        ];
    }

    $normalizedTabs = [];
    foreach (array_values($tabItems) as $index => $item) {
        $normalizedTabs[] = [
            'id' => (string) ($item['id'] ?? ($index === 0 ? 'map' : 'list')),
            'label' => (string) ($item['label'] ?? ($index === 0 ? $ns . '.tabs.map.label' : $ns . '.tabs.list.label')),
            'active' => (bool) ($item['active'] ?? $index === 0),
        ];
    }

    $context = [
        'use_design_comuni_list_demo' => (bool) ($blockData['use_design_comuni_list_demo'] ?? false),
        'breadcrumb' => $data['breadcrumb'] ?? [
            [
                'label' => $ns . '.breadcrumb.home.label',
                'url' => $localePrefix,
            ],
            [
                'label' => $ns . '.breadcrumb.elenco.label',
                'url' => null,
                'active' => true,
            ],
        ],
        'title' => $data['title'] ?? $ns . '.heading.title.label',
        'subtitle' => $data['subtitle'] ?? $ns . '.heading.subtitle.text',
        'tabs' => [
            'id' => (string) ($tabs['id'] ?? 'map-and-list'),
            'items' => $normalizedTabs,
        ],
        'main_content' => [
            'id' => (string) ($data['main_content']['id'] ?? 'filter-and-cards'),
            'filters' => is_array($filters) ? $filters : [],
            'cta' => is_array($cta) ? $cta : [],
        ],
        'map' => is_array($map) ? $map : ['data_url' => '/data/tickets.json'],
    ];

    $vm = app(TicketLayoutViewModel::class, ['data' => $context]);
    $initialSelectedTypes = $vm->selectedTypes();
    $initialSelectedStatuses = $vm->selectedStatuses();
@endphp

<div class="container" id="main-container">
<div
    x-data="{
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
    }"
    @filter-type-changed.window="toggleType($event.detail.type, $event.detail.checked)"
    @filter-status-changed.window="toggleStatus($event.detail.status, $event.detail.checked)"
>
        <div class="row justify-content-center mb-md-40 mb-lg-80">
            @include('pub_theme::components.blocks.ticket.heading', [
                'data' => array_merge($context, ['resolved_count' => $vm->resolvedLast12MonthsCount()]),
            ])
            <hr class="d-none d-lg-block mt-30 mb-2">
        </div>
        <div class="row justify-content-center">
            @include('pub_theme::components.blocks.ticket.map-filters-column', [
                'vm' => $vm,
                'ns' => $ns,
                'initialSelectedTypes' => $initialSelectedTypes,
            ])

            @include('pub_theme::components.blocks.ticket.column-main', [
                'vm' => $vm,
                'ns' => $ns,
                'includeCtaInPanels' => true,
            ])
        </div>

        @include('pub_theme::components.blocks.ticket.modal-disservizio', ['sprite' => $vm->sprite()])

        @if ($vm->hasSidebarFilters())
            <div class="modal d-lg-none" id="modal-categories" tabindex="-1" aria-labelledby="modal-categories-title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <h2 class="title-medium-semi-bold" id="modal-categories-title">{{ $vm->filtersTitle() }}</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Chiudi') }}"></button>
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
    </div>
</div>
