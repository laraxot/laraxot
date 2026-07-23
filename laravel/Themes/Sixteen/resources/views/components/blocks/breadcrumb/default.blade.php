@props(['data' => []])

@php
    $items = $data['items'] ?? [];
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-breadcrumbs" role="navigation">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb p-0" data-element="breadcrumb">
                        @foreach($items as $item)
                            @php
                                $isLast = $loop->last;
                                $label = $item['label'] ?? '';
                                $url = $item['url'] ?? '#';
                            @endphp
                            <li class="breadcrumb-item{{ $isLast ? ' active' : '' }}"{{ $isLast ? ' aria-current="page"' : '' }}>
                                @if($isLast)
                                    {{ $label }}
                                @else
                                    <a href="{{ $url }}">{{ $label }}</a>
                                    <span class="separator">/</span>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
