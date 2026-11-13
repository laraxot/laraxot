@props(['data' => []])

@php
    $items = $data['items'] ?? [];
    $translationNamespace = (string) ($data['translation_namespace'] ?? '');
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-breadcrumbs" role="navigation">
                <nav class="breadcrumb-container" aria-label="{{ __('fixcity::global.breadcrumb') }}">
                    <ol class="breadcrumb p-0" data-element="breadcrumb">
                        @foreach($items as $item)
                            @php
                                $isLast = $loop->last;
                                $label = $item['label'] ?? '';
                                if ($translationNamespace !== '') {
                                    $translated = __($translationNamespace.'.breadcrumb.'.$loop->index);
                                    $label = is_string($translated) ? $translated : $label;
                                }
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
