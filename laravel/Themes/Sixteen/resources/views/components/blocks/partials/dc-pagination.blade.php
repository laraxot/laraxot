{{--
  Design Comuni pager (markup `pagination-wrapper` di sito/lista-risorse.html) per un LengthAwarePaginator.
  Callers: blocks/blog/index (Novità), blocks/events-page/design-comuni (Eventi). Link veri con la query string, niente JavaScript.
  Input: $paginator (Illuminate\Contracts\Pagination\LengthAwarePaginator), $label (aria-label del nav).
  Finestra: pagina corrente +-2, piu' prima e ultima con ellissi. Bersagli 44px (WCAG 2.5.8).
--}}
@php
    $current = $paginator->currentPage();
    $last = max(1, $paginator->lastPage());
    $window = range(max(1, $current - 2), min($last, $current + 2));
    $t = static fn (string $key, array $replace = []): string => (string) __('blog::front_office.pagination.'.$key, $replace);
    $target = 'min-width:44px;min-height:44px';
@endphp
@if ($last > 1)
<nav class="pagination-wrapper justify-content-center" aria-label="{{ $label }}">
    <ul class="pagination">
        <li @class(['page-item', 'disabled' => $current <= 1])>
            <a class="page-link" style="{{ $target }}" @if ($current > 1) href="{{ $paginator->url($current - 1) }}" rel="prev" @else aria-disabled="true" @endif>
                <svg class="icon" width="16" height="16" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6l-6 6 6 6"/></svg>
                <span class="visually-hidden">{{ $t('previous') }}</span>
            </a>
        </li>
        @if ($window[0] > 1)
            <li class="page-item"><a class="page-link" style="{{ $target }}" href="{{ $paginator->url(1) }}" aria-label="{{ $t('page', ['page' => 1]) }}">1</a></li>
            @if ($window[0] > 2)<li class="page-item"><span class="page-link" aria-hidden="true">…</span></li>@endif
        @endif
        @foreach ($window as $page)
            <li class="page-item">
                @if ($page === $current)
                    <a class="page-link" style="{{ $target }}" href="{{ $paginator->url($page) }}" aria-current="page"><span class="visually-hidden">{{ $t('current', ['page' => $page, 'last' => $last]) }}</span><span aria-hidden="true">{{ $page }}</span></a>
                @else
                    <a class="page-link" style="{{ $target }}" href="{{ $paginator->url($page) }}" aria-label="{{ $t('page', ['page' => $page]) }}">{{ $page }}</a>
                @endif
            </li>
        @endforeach
        @if (end($window) < $last)
            @if (end($window) < $last - 1)<li class="page-item"><span class="page-link" aria-hidden="true">…</span></li>@endif
            <li class="page-item"><a class="page-link" style="{{ $target }}" href="{{ $paginator->url($last) }}" aria-label="{{ $t('page', ['page' => $last]) }}">{{ $last }}</a></li>
        @endif
        <li @class(['page-item', 'disabled' => $current >= $last])>
            <a class="page-link" style="{{ $target }}" @if ($current < $last) href="{{ $paginator->url($current + 1) }}" rel="next" @else aria-disabled="true" @endif>
                <span class="visually-hidden">{{ $t('next') }}</span>
                <svg class="icon" width="16" height="16" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
            </a>
        </li>
    </ul>
</nav>
@endif
