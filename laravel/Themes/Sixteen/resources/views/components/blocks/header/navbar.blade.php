@props(['menu_items' => [], 'secondary_items' => []])

{{-- Navbar - Bootstrap Italia Style --}}
<nav class="navbar navbar-expand-lg">
    <div class="container">
        {{-- Hamburger Toggle --}}
        <button class="custom-navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        {{-- Navbar Content --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            {{-- Primary Menu --}}
            <ul class="navbar-nav me-auto">
                @foreach($menu_items as $item)
                <li class="nav-item">
                    <a class="nav-link" href="{{ $item['url'] }}">
                        {{ $item['title'] }}
                    </a>
                </li>
                @endforeach
            </ul>
            
            {{-- Secondary Menu (if provided) --}}
            @if(count($secondary_items) > 0)
            <ul class="navbar-nav navbar-secondary">
                @foreach($secondary_items as $item)
                <li class="nav-item">
                    <a class="nav-link" href="{{ $item['url'] }}">
                        {{ $item['title'] }}
                    </a>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</nav>
