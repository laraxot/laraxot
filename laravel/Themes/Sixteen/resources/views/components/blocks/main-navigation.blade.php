@props(['data' => []])

{{-- 
    Main Navigation Component - Design Comuni Style
    Usage: <x-blocks.main-navigation :data="$navData" />
    
    Data structure:
    - menu_items: array
--}}

@php
    $menuItems = $data['menu_items'] ?? [
        ['label' => 'Amministrazione', 'url' => '/it/tests/amministrazione', 'submenu' => [
            ['label' => 'Organi di governo', 'url' => '#'],
            ['label' => 'Aree amministrative', 'url' => '#'],
            ['label' => 'Uffici', 'url' => '#'],
            ['label' => 'Enti e fondazioni', 'url' => '#'],
        ]],
        ['label' => 'Novità', 'url' => '/it/tests/novita', 'submenu' => [
            ['label' => 'Notizie', 'url' => '#'],
            ['label' => 'Comunicati', 'url' => '#'],
            ['label' => 'Avvisi', 'url' => '#'],
        ]],
        ['label' => 'Servizi', 'url' => '/it/tests/servizi', 'submenu' => []],
        ['label' => 'Vivere il Comune4', 'url' => '/it/tests/vivere', 'submenu' => [
            ['label' => 'Luoghi', 'url' => '#'],
            ['label' => 'Eventi', 'url' => '#'],
        ]],
        ['label' => 'Tutti gli argomenti', 'url' => '/it/tests/argomenti', 'highlighted' => true],
    ];
@endphp

<nav class="it-nav-wrapper bg-white border-bottom" aria-label="Navigazione principale">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="navbar navbar-expand-lg">
                    <button 
                        class="navbar-toggler" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#mainNavigation"
                        aria-controls="mainNavigation"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <span class="navbar-toggler-icon"></span>
                        <span class="sr-only">Nascondi la navigazione</span>
                    </button>
                    
                    <div class="collapse navbar-collapse" id="mainNavigation">
                        <ul class="navbar-nav">
                            @foreach($menuItems as $item)
                            <li class="nav-item {{ isset($item['highlighted']) ? 'highlighted' : '' }}">
                                <a 
                                    href="{{ $item['url'] }}" 
                                    class="nav-link px-3 py-3 {{ isset($item['highlighted']) ? 'fw-bold text-primary' : '' }}"
                                    @if(isset($item['submenu']) && count($item['submenu']) > 0)
                                        aria-haspopup="true" 
                                        aria-expanded="false"
                                    @endif
                                >
                                    {{ $item['label'] }}
                                </a>
                                
                                @if(isset($item['submenu']) && count($item['submenu']) > 0)
                                <div class="submenu-wrapper">
                                    <ul class="submenu-list">
                                        @foreach($item['submenu'] as $submenuItem)
                                        <li>
                                            <a href="{{ $submenuItem['url'] }}" class="submenu-link">
                                                {{ $submenuItem['label'] }}
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
