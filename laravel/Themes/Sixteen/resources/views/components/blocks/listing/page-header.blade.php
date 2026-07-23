@props([
    'breadcrumbs' => [],
    'eyebrow' => null,
    'title' => '',
    'summary' => '',
])

<div class="container">
    @if($breadcrumbs !== [])
        <nav class="cmp-breadcrumbs mt-4" aria-label="Breadcrumb">
            <ol class="breadcrumb">
                @foreach($breadcrumbs as $index => $item)
                    @if($index === count($breadcrumbs) - 1)
                        <li class="breadcrumb-item active" aria-current="page">{{ $item['label'] }}</li>
                    @else
                        <li class="breadcrumb-item">
                            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="row">
        <div class="col-12 col-lg-8">
            @if($eyebrow)
                <div class="cmp-text-button mt-2">
                    <p class="text-paragraph-small">{{ $eyebrow }}</p>
                </div>
            @endif

            <div class="cmp-heading pb-3 pb-lg-4">
                <h1>{{ $title }}</h1>
                @if($summary !== '')
                    <p class="lead">{{ $summary }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
