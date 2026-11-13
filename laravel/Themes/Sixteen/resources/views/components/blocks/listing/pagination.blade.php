@props([
    'current_page' => 1,
    'pages' => [],
    'previous_label' => __('pub_theme::components_common.pagination.previous_page.label'),
    'next_label' => __('pub_theme::components_common.pagination.next_page.label'),
])

<div class="container">
    <div class="row my-4">
        <nav class="pagination-wrapper justify-content-center" aria-label="{{ __('pub_theme::components_common.a11y.pagination.label') }}">
            <ul class="pagination">
                <li class="page-item {{ (int) $current_page === 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $pages[0]['url'] ?? '#' }}" {{ (int) $current_page === 1 ? 'tabindex="-1" aria-hidden="true"' : '' }}>
                        <svg class="icon icon-primary">
                            <use href="sprites.svg#it-chevron-left"></use>
                        </svg>
                        <span class="visually-hidden">{{ $previous_label }}</span>
                    </a>
                </li>

                @foreach($pages as $page)
                    <li class="page-item {{ (int) ($page['number'] ?? 0) === (int) $current_page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $page['url'] ?? '#' }}" {{ (int) ($page['number'] ?? 0) === (int) $current_page ? 'aria-current="page"' : '' }}>
                            @if((int) ($page['number'] ?? 0) === 1)
                                <span class="d-inline-block d-sm-none">{{ __('pub_theme::components_common.pagination.page.label') }} </span>
                            @endif
                            {{ $page['label'] ?? $page['number'] ?? '' }}
                        </a>
                    </li>
                @endforeach

                <li class="page-item {{ (int) $current_page === count($pages) ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $pages[count($pages) - 1]['url'] ?? '#' }}" {{ (int) $current_page === count($pages) ? 'tabindex="-1" aria-hidden="true"' : '' }}>
                        <span class="visually-hidden">{{ $next_label }}</span>
                        <svg class="icon icon-primary">
                            <use href="sprites.svg#it-chevron-right"></use>
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
