{{-- Bootstrap Italia Megamenu Component --}}

<li class="nav-item dropdown megamenu {{ ($fullWidth ?? false) ? 'full-width' : '' }}">
    <a class="nav-link dropdown-toggle"
       href="#"
       role="button"
       data-bs-toggle="dropdown"
       aria-expanded="false">
        {{ $title }}
    </a>
    <div class="dropdown-menu megamenu-menu">
        <div class="container-italia">
            <div class="row">
                @foreach($columns ?? [] as $column)
                <div class="col-12 col-lg-4">
                    <div class="megamenu-column">
                        @if(isset($column['title']))
                        <h3 class="megamenu-column-title">{{ $column['title'] }}</h3>
                        @endif
                        <ul class="megamenu-link-list">
                            @foreach($column['links'] ?? [] as $link)
                            <li>
                                <a href="{{ $link['url'] ?? '#' }}" class="megamenu-link">
                                    {{ $link['label'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</li>
