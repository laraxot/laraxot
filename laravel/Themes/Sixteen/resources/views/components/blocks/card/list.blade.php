{{--
    Card List Block - Bootstrap Italia Style
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/argomenti.html
--}}
@props(['data' => []])

@php
    $title = $data['title'] ?? 'Elenco';
    $items = $data['items'] ?? [];
@endphp

<div class="container py-5">
    @if($title)
    <h2 class="title-xxlarge mb-4">{{ $title }}</h2>
    @endif

    <div class="row g-4">
        @foreach($items as $item)
        <div class="col-md-6 col-xl-4">
            <div class="cmp-card-simple card-wrapper pb-0 rounded border border-light">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none" data-element="topic-element">
                            <h3 class="card-title t-primary title-xlarge">
                                {{ $item['title'] ?? 'Elemento' }}
                            </h3>
                        </a>
                        @if(isset($item['excerpt']))
                        <p class="text-secondary mb-0 description">
                            {{ $item['excerpt'] }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
