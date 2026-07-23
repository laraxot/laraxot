{{--
    Tabs Navigation - Mappa / Elenco
    Bootstrap Italia native tabs for Design Comuni parity
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazioni-elenco.html
--}}

@php
    $tabs = $tabs ?? [];
@endphp

<ul class="nav nav-tabs w-100 flex-nowrap border-bottom border-light mb-40 mt-3 shadow-none"
    id="tabDisservizio"
    role="tablist"
    data-section="{{ $tabsId ?? 'map-and-list' }}">
    @foreach ($tabs as $index => $tab)
        @php
            $tabId = $tab['id'] ?? 'map';
            $panelId = 'data-ex-disservizio'.($index + 1);
            $controlId = 'disservizio'.($index + 1);
            $isFirst = $index === 0;
        @endphp
        <li class="nav-item w-100" role="presentation">
            <button class="nav-link title-medium-semi-bold pt-0{{ $isFirst ? ' active' : '' }}"
                    @click="activeTab = '{{ $tabId }}'"
                    role="tab"
                    id="tab-{{ $tabId }}"
                    :aria-selected="activeTab === '{{ $tabId }}'"
                    aria-controls="{{ $controlId }}">
                {{ $tab['label'] ?? '' }}
            </button>
        </li>
    @endforeach
</ul>
