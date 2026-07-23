@props([
    'tabs' => [],
    'sectionId' => 'map-and-list',
])

{{-- HTML parity: https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html — Bootstrap Italia native tabs --}}
@if (!empty($tabs))
<ul class="nav nav-tabs w-100 flex-nowrap border-bottom border-light mb-40 mt-3 shadow-none"
    id="tabDisservizio"
    role="tablist"
    data-section="{{ $sectionId }}">
    @foreach ($tabs as $index => $tab)
        @php
            $tabId = $tab['id'] ?? 'map';
            $panelId = 'data-ex-disservizio'.($index + 1);
            $controlId = 'disservizio'.($index + 1);
            $isFirst = $index === 0;
        @endphp
        <li class="nav-item w-100" role="tab">
            <a class="nav-link title-medium-semi-bold pt-0{{ $isFirst ? ' active' : '' }}"
               href="#{{ $panelId }}"
               data-bs-toggle="tab"
               role="button"
               aria-controls="{{ $controlId }}"
               aria-selected="{{ $isFirst ? 'true' : 'false' }}">
                {{ $tab['label'] ?? '' }}
            </a>
        </li>
    @endforeach
</ul>
@endif