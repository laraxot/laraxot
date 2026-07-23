{{-- 
/**
 * Skiplinks Component - Bootstrap Italia Compliant
 * 
 * Provides navigation shortcuts for keyboard and assistive technology users
 * Essential for PA website accessibility compliance
 * 
 * @param array $links Array of skiplink items with 'href', 'text', and optional 'external' properties
 * @param string $ariaLabel Accessible label for the navigation (default: 'Scorciatoie di navigazione')
 * @param bool $showList Whether to render as a list (default: true) or as simple div
 */
--}}

@props([
    'links' => [
        ['href' => '#main-content', 'text' => 'Vai al contenuto principale'],
        ['href' => '#footer', 'text' => 'Vai al piede di pagina']
    ],
    'ariaLabel' => 'Scorciatoie di navigazione',
    'showList' => true
])

@if($showList)
<nav class="skiplinks" aria-label="{{ $ariaLabel }}">
    <ul>
        @foreach($links as $link)
        <li class="visually-hidden-focusable">
            <a href="{{ $link['href'] }}"
               @if(isset($link['external']) && $link['external'])
               target="_blank"
               rel="noopener noreferrer"
               @endif>
                {{ $link['text'] }}
                @if(isset($link['external']) && $link['external'])
                <span class="sr-only">(link esterno)</span>
                @endif
            </a>
        </li>
        @endforeach
    </ul>
</nav>
@else
<div class="skiplinks">
    @foreach($links as $link)
    <a class="visually-hidden-focusable" 
       href="{{ $link['href'] }}"
       @if(isset($link['external']) && $link['external'])
       target="_blank"
       rel="noopener noreferrer"
       @endif>
        {{ $link['text'] }}
        @if(isset($link['external']) && $link['external'])
        <span class="sr-only">(link esterno)</span>
        @endif
    </a>
    @endforeach
</div>
@endif

{{-- 
Usage Examples:

1. Basic skiplinks (default):
<x-pub_theme::skiplinks />

2. Custom skiplinks:
<x-pub_theme::skiplinks 
<x-pub_theme::skiplinks />

2. Custom skiplinks:
<x-pub_theme::skiplinks 
    :links="[
        ['href' => '#nav', 'text' => 'Vai al menu'],
        ['href' => '#content', 'text' => 'Vai al contenuto'],
        ['href' => '#footer', 'text' => 'Vai al piede'],
        ['href' => 'https://form.agid.gov.it/view/xyz', 'text' => 'Dichiarazione di accessibilità', 'external' => true]
    ]"
    aria-label="Scorciatoie di navigazione personalizzate" />

3. Simple div version:
<x-pub_theme::skiplinks :show-list="false" />
<x-pub_theme::skiplinks :show-list="false" />
<x-pub_theme::skiplinks :show-list="false" />
--}}
