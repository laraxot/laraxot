{{--
    Paginazione dell'elenco: link veri con la query string dei filtri (`?...&page=N#data-ex-disservizio2`), nessun JavaScript.
    Markup `pagination-wrapper` del riferimento (listing). Usa $list, $filters, $base, $t, $icon del blocco.
--}}
@php
    $current = $list['page'];
    $last = $list['last_page'];
    $pageUrl = static fn (int $page): string => $base.'?'.http_build_query(array_merge($filters->toQuery(['page']), ['page' => $page])).'#data-ex-disservizio2';
    $window = range(max(1, $current - 2), min($last, $current + 2));
    $from = ($current - 1) * $list['per_page'] + 1;
    $to = min($list['total'], $current * $list['per_page']);
    // il riferimento ha bersagli di 40 px: sotto i 44 px consigliati per il tocco, lo stile in riga e' solo il minimo (nessun CSS per slug)
@endphp
<div class="col-12">
    <p class="subtitle-small text-center mb-0">{{ $t('results.summary', ['from' => $from, 'to' => $to, 'total' => $list['total']]) }}</p>
    <nav class="pagination-wrapper justify-content-center" aria-label="{{ $t('pagination.label') }}">
        <ul class="pagination">
            <li @class(['page-item', 'disabled' => $current === 1])>
                <a class="page-link" style="min-width:44px;min-height:44px" @if ($current > 1) href="{{ $pageUrl($current - 1) }}" rel="prev" @else tabindex="-1" aria-disabled="true" @endif><svg class="icon icon-primary" aria-hidden="true"><use href="{{ $icon('it-chevron-left') }}"></use></svg>
                    <span class="visually-hidden">{{ $t('pagination.previous_label') }}</span></a>
            </li>
            @if ($window[0] > 1)
                <li class="page-item"><a class="page-link" style="min-width:44px;min-height:44px" href="{{ $pageUrl(1) }}" aria-label="{{ $t('pagination.page_label', ['page' => 1]) }}">1</a></li>
                @if ($window[0] > 2)<li class="page-item"><span class="page-link" aria-hidden="true">…</span></li>@endif
            @endif
            @foreach ($window as $page)
                <li class="page-item">
                    <a class="page-link" style="min-width:44px;min-height:44px" href="{{ $pageUrl($page) }}" @if ($page === $current) aria-current="page" @else aria-label="{{ $t('pagination.page_label', ['page' => $page]) }}" @endif>@if ($page === $current)<span class="visually-hidden">{{ $t('pagination.current', ['page' => $page, 'last' => $last]) }}</span><span aria-hidden="true">{{ $page }}</span>@else{{ $page }}@endif</a>
                </li>
            @endforeach
            @if (end($window) < $last)
                @if (end($window) < $last - 1)<li class="page-item"><span class="page-link" aria-hidden="true">…</span></li>@endif
                <li class="page-item"><a class="page-link" style="min-width:44px;min-height:44px" href="{{ $pageUrl($last) }}" aria-label="{{ $t('pagination.page_label', ['page' => $last]) }}">{{ $last }}</a></li>
            @endif
            <li @class(['page-item', 'disabled' => $current === $last])>
                <a class="page-link" style="min-width:44px;min-height:44px" @if ($current < $last) href="{{ $pageUrl($current + 1) }}" rel="next" @else tabindex="-1" aria-disabled="true" @endif><span class="visually-hidden">{{ $t('pagination.next_label') }}</span>
                    <svg class="icon icon-primary" aria-hidden="true"><use href="{{ $icon('it-chevron-right') }}"></use></svg></a>
            </li>
        </ul>
    </nav>
</div>
