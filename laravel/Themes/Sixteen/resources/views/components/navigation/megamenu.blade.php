{{-- 
/**
 * Megamenu Component - Bootstrap Italia Compliant
 * 
 * Complex navigation dropdown displaying lists of links and related content
 * Variation of the dropdown component for navbar navigation
 * 
 * @param string $id Unique ID for the megamenu
 * @param string $title Title/trigger text for the megamenu
 * @param array $columns Array of megamenu columns with links
 * @param string $exploreText "Explore section" link text
 * @param string $exploreUrl "Explore section" link URL
 * @param string $exploreAllText "Explore all" link text  
 * @param string $exploreAllUrl "Explore all" link URL
 * @param array $cta Call-to-action configuration
 * @param string $theme Theme variant: 'light', 'dark-desktop', 'dark-mobile'
 * @param bool $fullWidth Whether to use full width layout
 */
--}}

@props([
    'id' => 'megamenu-' . uniqid(),
    'title' => 'Megamenu',
    'columns' => [],
    'exploreText' => '',
    'exploreUrl' => '#',
    'exploreAllText' => '',
    'exploreAllUrl' => '#',
    'cta' => [],
    'theme' => 'light', // 'light', 'dark-desktop', 'dark-mobile'
    'fullWidth' => false
])

@php
    $dropdownId = $id . '-dropdown';
    $buttonId = $id . '-button';
    
    // Build theme classes
    $themeClasses = [];
    switch ($theme) {
        case 'dark-desktop':
            $themeClasses[] = 'theme-light-desk';
            break;
        case 'dark-mobile':
            $themeClasses[] = 'theme-dark-mobile';
            break;
        default:
            // light theme - no additional classes
            break;
    }
@endphp

<li class="nav-item dropdown has-megamenu">
    <button 
        class="nav-link dropdown-toggle" 
        type="button"
        id="{{ $buttonId }}"
        data-bs-toggle="dropdown" 
        data-bs-target="#{{ $dropdownId }}"
        aria-expanded="false"
        aria-haspopup="true"
        aria-controls="{{ $dropdownId }}"
    >
        <span>{{ $title }}</span>
        <svg class="icon icon-xs">
            <use href="#it-expand"></use>
        </svg>
    </button>
    
    <div 
        class="dropdown-menu megamenu {{ $fullWidth ? 'full-width' : '' }}" 
        id="{{ $dropdownId }}"
        role="region"
        aria-labelledby="{{ $buttonId }}"
    >
        <div class="megamenu-head">
            <h3 class="megamenu-title">{{ $title }}</h3>
        </div>
        
        <div class="megamenu-body">
            @if(!empty($columns))
                <div class="row">
                    @foreach($columns as $column)
                        <div class="col-xs-12 col-lg-4 megamenu-col">
                            @if(isset($column['title']))
                                <h4 class="megamenu-col-title">{{ $column['title'] }}</h4>
                            @endif
                            
                            @if(isset($column['links']) && !empty($column['links']))
                                <ul class="link-list">
                                    @foreach($column['links'] as $link)
                                        <li class="nav-item">
                                            <a 
                                                class="nav-link" 
                                                href="{{ $link['url'] ?? '#' }}"
                                                @if(isset($link['target'])) target="{{ $link['target'] }}" @endif
                                                @if(isset($link['title'])) title="{{ $link['title'] }}" @endif
                                            >
                                                <span>{{ $link['label'] }}</span>
                                                @if(isset($link['description']))
                                                    <span class="visually-hidden">{{ $link['description'] }}</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            
                            @if(isset($column['exploreLink']))
                                <div class="megamenu-col-footer">
                                    <a href="{{ $column['exploreLink']['url'] ?? '#' }}" class="btn btn-xs btn-outline-primary">
                                        {{ $column['exploreLink']['label'] }}
                                        <span class="visually-hidden"> {{ $column['exploreLink']['section'] ?? '' }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Slot-based content --}}
                {{ $slot }}
            @endif
            
            {{-- Explore section link --}}
            @if($exploreText && $exploreUrl)
                <div class="megamenu-footer mt-3">
                    <a href="{{ $exploreUrl }}" class="btn btn-xs btn-outline-primary">
                        {{ $exploreText }}
                    </a>
                </div>
            @endif
            
            {{-- Explore all link --}}
            @if($exploreAllText && $exploreAllUrl)
                <div class="megamenu-footer mt-2">
                    <a href="{{ $exploreAllUrl }}" class="btn btn-xs btn-primary">
                        {{ $exploreAllText }}
                    </a>
                </div>
            @endif
            
            {{-- Call to action --}}
            @if(!empty($cta))
                <div class="megamenu-cta {{ $cta['position'] ?? 'bottom' }}">
                    @if(isset($cta['image']))
                        <div class="megamenu-cta-img">
                            <img src="{{ $cta['image'] }}" alt="{{ $cta['imageAlt'] ?? '' }}" class="img-fluid">
                        </div>
                    @endif
                    
                    <div class="megamenu-cta-content">
                        @if(isset($cta['title']))
                            <h4>{{ $cta['title'] }}</h4>
                        @endif
                        
                        @if(isset($cta['description']))
                            <p>{{ $cta['description'] }}</p>
                        @endif
                        
                        @if(isset($cta['button']))
                            <a 
                                href="{{ $cta['button']['url'] ?? '#' }}" 
                                class="btn {{ $cta['button']['class'] ?? 'btn-primary' }}"
                                @if(isset($cta['button']['target'])) target="{{ $cta['button']['target'] }}" @endif
                            >
                                {{ $cta['button']['label'] }}
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</li>

{{-- 
Usage Examples:

1. Basic megamenu with columns:
<x-pub_theme::megamenu 
<x-pub_theme::megamenu 
<x-pub_theme::megamenu 
    title="Servizi"
    :columns="[
        [
            'title' => 'Categoria 1',
            'links' => [
                ['label' => 'Link lista 1', 'url' => '/link1'],
                ['label' => 'Link lista 2', 'url' => '/link2'],
                ['label' => 'Link lista 3', 'url' => '/link3']
            ],
            'exploreLink' => ['label' => 'Esplora la sezione', 'url' => '/categoria1', 'section' => 'Categoria 1']
        ],
        [
            'title' => 'Categoria 2', 
            'links' => [
                ['label' => 'Link lista 4', 'url' => '/link4'],
                ['label' => 'Link lista 5', 'url' => '/link5'],
                ['label' => 'Link lista 6', 'url' => '/link6']
            ]
        ],
        [
            'title' => 'Categoria 3',
            'links' => [
                ['label' => 'Link lista 7', 'url' => '/link7'],
                ['label' => 'Link lista 8', 'url' => '/link8'],
                ['label' => 'Link lista 9', 'url' => '/link9']
            ]
        ]
    ]"
    explore-text="Esplora la sezione servizi"
    explore-url="/servizi" />

2. Megamenu with call-to-action:
<x-pub_theme::megamenu 
<x-pub_theme::megamenu 
<x-pub_theme::megamenu 
    title="Informazioni"
    :columns="$infoColumns"
    :cta="[
        'position' => 'right',
        'title' => 'Hai bisogno di aiuto?',
        'description' => 'Contatta il nostro supporto per assistenza.',
        'button' => ['label' => 'Contattaci', 'url' => '/contatti', 'class' => 'btn-primary'],
        'image' => '/images/support.jpg',
        'imageAlt' => 'Supporto clienti'
    ]" />

3. Dark theme megamenu:
<x-pub_theme::megamenu 
<x-pub_theme::megamenu 
<x-pub_theme::megamenu 
    title="Documenti"
    theme="dark-desktop"
    :full-width="true"
    :columns="$documentsColumns"
    explore-all-text="Esplora tutti i documenti"
    explore-all-url="/documenti" />

4. Megamenu with accessible links:
<x-pub_theme::megamenu 
<x-pub_theme::megamenu 
<x-pub_theme::megamenu 
    title="Amministrazione"
    :columns="[
        [
            'links' => [
                [
                    'label' => 'Esplora tutti',
                    'url' => '/uffici',
                    'description' => 'i contenuti della sezione Uffici'
                ],
                [
                    'label' => 'Esplora tutti',
                    'url' => '/organigramma', 
                    'description' => 'i contenuti della sezione Organigramma'
                ]
            ]
        ]
    ]" />

5. Custom content with slot:
<x-pub_theme::megamenu title="Custom Menu">
<x-pub_theme::megamenu title="Custom Menu">
<x-pub_theme::megamenu title="Custom Menu">
    <div class="row">
        <div class="col-12">
            <h4>Contenuto personalizzato</h4>
            <p>Qui puoi inserire qualsiasi contenuto HTML personalizzato.</p>
            <a href="/custom" class="btn btn-primary">Vai alla pagina</a>
        </div>
    </div>
</x-pub_theme::megamenu>
</x-pub_theme::megamenu>
</x-pub_theme::megamenu>

Navigation Integration:
Place megamenu items within a navbar with .has-megamenu class:

<nav class="navbar navbar-expand-lg has-megamenu {{ $themeClasses ? implode(' ', $themeClasses) : '' }}">
    <div class="navbar-nav">
        <x-pub_theme::megamenu ... />
        <x-pub_theme::megamenu ... />
        <x-pub_theme::megamenu ... />
        <x-pub_theme::megamenu ... />
        <x-pub_theme::megamenu ... />
        <x-pub_theme::megamenu ... />
    </div>
</nav>

Bootstrap Italia Classes Reference:
- .has-megamenu: Applied to both navbar and nav-item dropdown
- .megamenu: Main megamenu container
- .megamenu-head: Header section
- .megamenu-body: Main content area
- .megamenu-col: Individual column
- .megamenu-col-title: Column title
- .megamenu-col-footer: Column footer with links
- .megamenu-footer: Main footer area
- .megamenu-cta: Call-to-action section
- .theme-light-desk: Light navbar with dark megamenu on desktop
- .theme-dark-mobile: Dark navbar and megamenu on mobile
- .full-width: Full width megamenu layout
--}}
