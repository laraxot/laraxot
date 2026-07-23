@props(['data' => []])

@php
    use Modules\Fixcity\ViewModels\TicketLayoutViewModel;

    $columns = (int) ($data['columns'] ?? 2);
    $layout = (string) ($data['layout'] ?? 'tailwind');
    $items = $data['items'] ?? [];
    $ticketContext = $data['ticket_context'] ?? [];
    $vm = $ticketContext !== [] ? app(TicketLayoutViewModel::class, ['data' => $ticketContext]) : null;
    $initialSelectedTypes = $vm?->selectedTypes() ?? [];
    $initialSelectedStatuses = $vm?->selectedStatuses() ?? [];
    $ns = 'fixcity::ticket';
@endphp

@if ($layout === 'design-comuni' && $vm !== null)
    <div
        class="container"
        id="main-container"
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
                document.querySelectorAll('[data-filter-type=\"' + type + '\"]').forEach(function (cb) {
                    cb.checked = checked;
                });
                this.dispatchMapFilters();
            },
            toggleStatus(status, checked) {
                document.querySelectorAll('[data-filter-status=\"' + status + '\"]').forEach(function (cb) {
                    cb.checked = checked;
                });
                this.dispatchMapFilters();
            },
        }"
        @filter-type-changed.window="toggleType($event.detail.type, $event.detail.checked)"
        @filter-status-changed.window="toggleStatus($event.detail.status, $event.detail.checked)"
    >
            @if (!empty($ticketContext['title']) || !empty($ticketContext['breadcrumb']))
                @include('pub_theme::components.blocks.ticket.heading', [
                    'data' => array_merge($ticketContext, ['resolved_count' => $vm->resolvedLast12MonthsCount()]),
                ])
            @endif
            <div class="row justify-content-center mb-md-40 mb-lg-80">
                @if (!empty($ticketContext['title']) || !empty($ticketContext['breadcrumb']))
                    <hr class="d-none d-lg-block mt-30 mb-2">
                @endif
                @foreach ($items as $item)
                    @php
                        $itemData = is_array($item['data'] ?? null) ? $item['data'] : [];
                        $view = (string) ($itemData['view'] ?? '');
                        $role = (string) ($item['type'] ?? '');
                    @endphp
                    @if ($view !== '' && view()->exists($view))
                        @if ($role === 'map-filters')
                            @include($view, [
                                'vm' => $vm,
                                'ns' => $ns,
                                'initialSelectedTypes' => $initialSelectedTypes,
                            ])
                        @elseif ($role === 'map')
                            @include($view, [
                                'vm' => $vm,
                                'ns' => $ns,
                                'includeCtaInPanels' => (bool) ($data['include_cta_in_map_panel'] ?? true),
                            ])
                        @else
                            @include($view, array_merge(['data' => $itemData], ['vm' => $vm]))
                        @endif
                    @endif
                @endforeach
            </div>

            @include('pub_theme::components.blocks.ticket.modal-disservizio', ['sprite' => $vm->sprite()])

            @if ($vm->hasSidebarFilters())
                <div class="modal fade d-lg-none" id="modal-categories" tabindex="-1" aria-labelledby="modal-categories-title" aria-hidden="true">
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

            <style>.leaflet-container { z-index: 1; }</style>
    </div>
@else
    <div class="grid-block">
        <div class="grid-wrapper grid grid-cols-1 {{ $columns === 2 ? 'md:grid-cols-2' : 'lg:grid-cols-3' }} gap-6">
            @foreach ($items as $item)
                @php
                    $itemData = is_array($item['data'] ?? null) ? $item['data'] : [];
                    $view = (string) ($itemData['view'] ?? $item['blade'] ?? '');
                @endphp
                @if ($view !== '' && view()->exists($view))
                    <div class="grid-item">
                        @include($view, ['data' => $itemData])
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif
