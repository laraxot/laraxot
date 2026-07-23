{{-- 
/**
 * Sidebar Component - Bootstrap Italia Compliant
 * 
 * Lateral navigation bar with link lists and nested link lists
 * Can contain headers, primary links, and secondary links
 * 
 * @param string $id Unique ID for the sidebar
 * @param string $title Sidebar header title
 * @param string $subtitle Optional subtitle for the header
 * @param array $links Array of navigation links
 * @param bool $secondary Whether to use secondary styling
 * @param bool $nested Whether to include nested navigation
 * @param bool $dark Whether to use dark theme
 * @param bool $iconLine Whether to show icon line divider
 * @param string $iconLinePosition Position of icon line: 'left' or 'right'
 * @param string $headerIcon Icon for the header
 */
--}}

@props([
    'id' => 'sidebar-' . uniqid(),
    'title' => '',
    'subtitle' => '',
    'links' => [],
    'secondary' => false,
    'nested' => false,
    'dark' => false,
    'iconLine' => false,
    'iconLinePosition' => 'right', // 'left' or 'right'
    'headerIcon' => ''
])

@php
    $sidebarClasses = collect(['sidebar-wrapper']);
    if ($dark) {
        $sidebarClasses->push('theme-dark');
    }
    
    $linklistClasses = collect(['sidebar-linklist-wrapper']);
    if ($secondary) {
        $linklistClasses->push('linklist-secondary');
    }
    
    $headerClasses = collect(['sidebar-header']);
    if ($iconLine) {
        $headerClasses->push($iconLinePosition === 'left' ? 'border-light-left' : 'border-light-right');
    }
@endphp

<nav class="{{ $sidebarClasses->implode(' ') }}" id="{{ $id }}">
    @if($title || $subtitle)
        <div class="{{ $headerClasses->implode(' ') }}">
            @if($headerIcon)
                <div class="sidebar-icon">
                    <svg class="icon">
                        <use href="#{{ $headerIcon }}"></use>
                    </svg>
                </div>
            @endif
            
            @if($title)
                <h4>{{ $title }}</h4>
            @endif
            
            @if($subtitle)
                <span>{{ $subtitle }}</span>
            @endif
        </div>
    @endif
    
    <div class="{{ $linklistClasses->implode(' ') }}">
        @if(!empty($links))
            <ul class="link-list">
                @foreach($links as $link)
                    @php
                        $linkClasses = collect(['list-item']);
                        if (isset($link['active']) && $link['active']) {
                            $linkClasses->push('active');
                        }
                        
                        $navLinkClasses = collect(['list-link']);
                        if (isset($link['disabled']) && $link['disabled']) {
                            $navLinkClasses->push('disabled');
                        }
                    @endphp
                    
                    <li class="{{ $linkClasses->implode(' ') }}">
                        @if(isset($link['disabled']) && $link['disabled'])
                            <span class="{{ $navLinkClasses->implode(' ') }}">
                                @if(isset($link['icon']))
                                    <svg class="icon icon-sm">
                                        <use href="#{{ $link['icon'] }}"></use>
                                    </svg>
                                @endif
                                <span>{{ $link['label'] }}</span>
                            </span>
                        @else
                            <a 
                                href="{{ $link['url'] ?? '#' }}" 
                                class="{{ $navLinkClasses->implode(' ') }}"
                                @if(isset($link['target'])) target="{{ $link['target'] }}" @endif
                                @if(isset($link['title'])) title="{{ $link['title'] }}" @endif
                                @if(isset($link['active']) && $link['active']) aria-current="page" @endif
                            >
                                @if(isset($link['icon']))
                                    <svg class="icon icon-sm">
                                        <use href="#{{ $link['icon'] }}"></use>
                                    </svg>
                                @endif
                                <span>{{ $link['label'] }}</span>
                            </a>
                        @endif
                        
                        {{-- Nested links --}}
                        @if($nested && isset($link['children']) && !empty($link['children']))
                            <ul class="link-sublist">
                                @foreach($link['children'] as $childLink)
                                    @php
                                        $childClasses = collect(['list-item']);
                                        if (isset($childLink['active']) && $childLink['active']) {
                                            $childClasses->push('active');
                                        }
                                        
                                        $childNavClasses = collect(['list-link']);
                                        if (isset($childLink['disabled']) && $childLink['disabled']) {
                                            $childNavClasses->push('disabled');
                                        }
                                    @endphp
                                    
                                    <li class="{{ $childClasses->implode(' ') }}">
                                        @if(isset($childLink['disabled']) && $childLink['disabled'])
                                            <span class="{{ $childNavClasses->implode(' ') }}">
                                                <span>{{ $childLink['label'] }}</span>
                                            </span>
                                        @else
                                            <a 
                                                href="{{ $childLink['url'] ?? '#' }}" 
                                                class="{{ $childNavClasses->implode(' ') }}"
                                                @if(isset($childLink['target'])) target="{{ $childLink['target'] }}" @endif
                                                @if(isset($childLink['title'])) title="{{ $childLink['title'] }}" @endif
                                                @if(isset($childLink['active']) && $childLink['active']) aria-current="page" @endif
                                            >
                                                <span>{{ $childLink['label'] }}</span>
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            {{-- Slot-based content --}}
            {{ $slot }}
        @endif
    </div>
</nav>

{{-- 
Usage Examples:

1. Simple sidebar with header:
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
    title="Sezione del sito"
    :links="[
        ['label' => 'Link lista 1', 'url' => '/link1', 'active' => true],
        ['label' => 'Link lista 2', 'url' => '/link2', 'disabled' => true],
        ['label' => 'Link lista 3', 'url' => '/link3'],
        ['label' => 'Link lista 4', 'url' => '/link4']
    ]" />

2. Sidebar with icon in header:
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
    title="Documenti"
    subtitle="Area riservata"
    header-icon="it-folder"
    :icon-line="true"
    icon-line-position="right"
    :links="$documentLinks" />

3. Secondary styled sidebar:
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
    title="Menu secondario"
    :secondary="true"
    :links="[
        ['label' => 'Informazioni', 'url' => '/info'],
        ['label' => 'Contatti', 'url' => '/contatti'],
        ['label' => 'FAQ', 'url' => '/faq']
    ]" />

4. Nested sidebar navigation:
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
    title="Amministrazione"
    :nested="true"
    :links="[
        [
            'label' => 'Organigramma',
            'url' => '/organigramma',
            'icon' => 'it-user',
            'children' => [
                ['label' => 'Sindaco', 'url' => '/organigramma/sindaco'],
                ['label' => 'Giunta', 'url' => '/organigramma/giunta'],
                ['label' => 'Consiglio', 'url' => '/organigramma/consiglio']
            ]
        ],
        [
            'label' => 'Uffici',
            'url' => '/uffici',
            'icon' => 'it-pa',
            'children' => [
                ['label' => 'Anagrafe', 'url' => '/uffici/anagrafe'],
                ['label' => 'Tributi', 'url' => '/uffici/tributi'],
                ['label' => 'Urbanistica', 'url' => '/uffici/urbanistica']
            ]
        ],
        [
            'label' => 'Documenti',
            'url' => '/documenti',
            'icon' => 'it-files'
        ]
    ]" />

5. Dark theme sidebar:
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
    title="Navigazione"
    :dark="true"
    :links="$navigationLinks" />

6. Sidebar with mixed states:
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
<x-pub_theme::sidebar 
    title="Menu completo"
    :links="[
        ['label' => 'Home', 'url' => '/', 'icon' => 'it-home', 'active' => true],
        ['label' => 'Servizi', 'url' => '/servizi', 'icon' => 'it-settings'],
        ['label' => 'In manutenzione', 'url' => '#', 'icon' => 'it-tool', 'disabled' => true],
        ['label' => 'Contatti', 'url' => '/contatti', 'icon' => 'it-mail', 'target' => '_blank', 'title' => 'Apre in nuova finestra']
    ]" />

7. Custom content with slot:
<x-pub_theme::sidebar title="Menu personalizzato">
<x-pub_theme::sidebar title="Menu personalizzato">
<x-pub_theme::sidebar title="Menu personalizzato">
    <ul class="link-list">
        <li class="list-item active">
            <a href="#" class="list-link">
                <svg class="icon icon-sm">
                    <use href="#it-star-outline"></use>
                </svg>
                <span>Elemento personalizzato</span>
            </a>
        </li>
        <li class="list-item">
            <a href="#" class="list-link">
                <span>Altro elemento</span>
            </a>
        </li>
    </ul>
    
    <div class="mt-3">
        <button class="btn btn-primary btn-sm">Azione personalizzata</button>
    </div>
</x-pub_theme::sidebar>
</x-pub_theme::sidebar>
</x-pub_theme::sidebar>

Layout Integration:
Use sidebar in a layout with main content:

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-3">
            <x-pub_theme::sidebar ... />
            <x-pub_theme::sidebar ... />
            <x-pub_theme::sidebar ... />
        </div>
        <div class="col-12 col-lg-9">
            <!-- Main content -->
        </div>
    </div>
</div>

Bootstrap Italia Classes Reference:
- .sidebar-wrapper: Main sidebar container
- .sidebar-header: Header section
- .sidebar-linklist-wrapper: Links container
- .linklist-secondary: Secondary styling for links
- .link-list: Main link list
- .link-sublist: Nested link list
- .list-item: Individual list item
- .list-link: Link styling
- .theme-dark: Dark theme variant
- .border-light-left/.border-light-right: Icon line positioning
- .active: Active state
- .disabled: Disabled state
--}}
