{{--
    Breadcrumb - Design Comuni Style (Bootstrap Italia)
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazioni-elenco.html
--}}

@php
    $items = $data['breadcrumb'] ?? [];
@endphp

<div class="cmp-breadcrumbs" role="navigation">
    <nav class="breadcrumb-container" aria-label="breadcrumb">
        <ol class="breadcrumb p-0" data-element="breadcrumb">
            @foreach ($items as $index => $item)
                @php
                    $isActive = !($item['url'] ?? false);
                @endphp
                <li class="breadcrumb-item{{ $isActive ? ' active' : '' }}"{{ $isActive ? ' aria-current="page"' : '' }}>
                    @if ($item['url'] ?? false)
                        <a href="{{ $item['url'] }}">{{ __($item['label']) }}</a><span class="separator">/</span>
                    @else
                        {{ __($item['label']) }}
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
</div>