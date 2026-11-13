{{-- Bootstrap Italia Sidebar Component --}}

<div class="sidebar-wrapper">
    @if(isset($title))
    <div class="sidebar-header">
        <h3 class="sidebar-title">{{ $title }}</h3>
    </div>
    @endif
    <nav class="sidebar-nav" aria-label="{{ $title ?? 'Sidebar navigation' }}">
        <ul class="sidebar-link-list">
            @foreach($links ?? [] as $link)
            <li class="sidebar-item">
                <a href="{{ $link['url'] ?? '#' }}"
                   class="sidebar-link {{ ($link['active'] ?? false) ? 'active' : '' }}"
                   {{ ($link['active'] ?? false) ? 'aria-current="page"' : '' }}>
                    {{ $link['label'] }}
                </a>
            </li>
            @endforeach
        </ul>
    </nav>
</div>
