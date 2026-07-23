@props([
    'vm' => null,
    'ns' => 'fixcity::ticket',
    'initialSelectedTypes' => [],
])

@if ($vm !== null && $vm->hasSidebarFilters())
    <div class="col-lg-3 d-none d-lg-block" id="{{ $vm->filtersSectionId() }}" aria-label="{{ $vm->filtersTitle() }}">
        @include('pub_theme::components.blocks.ticket.filters-sidebar', [
            'filters' => $vm->filters(),
            'statusFilters' => $vm->statusFilters(),
            'selectedTypes' => $initialSelectedTypes,
            'selectedStatuses' => $vm->selectedStatuses(),
            'context' => 'desktop',
        ])
    </div>
@endif
