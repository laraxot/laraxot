{{-- Bootstrap Italia Bottom Navigation Component --}}
@props([
    'items' => [],
    'fixed' => false,
    'hiddenOnDesktop' => false,
])

<nav class="bottom-nav {{ $fixed ? 'fixed-bottom' : '' }} {{ $hiddenOnDesktop ? 'd-lg-none' : '' }}"
     aria-label="{{ __('pub_theme::bootstrap_italia.bottom_nav.bottom_navigation.label') }}">
    <ul class="bottom-nav-list">
        @foreach($items as $item)
        <li class="bottom-nav-item">
            <a href="{{ $item['url'] ?? '#' }}"
               class="bottom-nav-link {{ ($item['active'] ?? false) ? 'active' : '' }}"
               {{ ($item['active'] ?? false) ? 'aria-current="page"' : '' }}>
                @if(isset($item['icon']))
                <svg class="icon bottom-nav-icon" aria-hidden="true">
                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#{{ $item['icon'] }}"></use>
                </svg>
                @endif
                <span class="bottom-nav-label">{{ $item['label'] }}</span>
            </a>
        </li>
        @endforeach
    </ul>
</nav>
