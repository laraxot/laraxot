{{--
    Hero Topic Block
    Reference: design-comuni-pagine-statiche argomento detail page
--}}
@props([
    'title' => '',
    'subtitle' => '',
    'content' => '',
    'image' => 'https://picsum.photos/1280/720',
    'breadcrumb' => [],
    'managing_offices' => [],
])

<div class="it-hero-wrapper it-wrapped-container" id="main-container">
    {{-- Breadcrumb --}}
    @if(!empty($breadcrumb))
        <nav aria-label="Breadcrumb" class="container pt-3">
            <ol class="breadcrumb">
                @foreach($breadcrumb as $item)
                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                        @if($loop->last || empty($item['url']))
                            {{ $item['label'] ?? '' }}
                        @else
                            <a href="{{ $item['url'] }}">{{ $item['label'] ?? '' }}</a>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif

    {{-- Hero Image --}}
    <div class="img-responsive-wrapper">
        <div class="img-responsive">
            <div class="img-wrapper">
                <img src="{{ $image }}" alt="{{ $title }}" title="{{ $title }}">
            </div>
        </div>
    </div>

    {{-- Hero Card --}}
    <div class="container">
        <div class="row">
            <div class="col-12 drop-shadow">
                <div class="it-hero-card it-hero-bottom-overlapping rounded px-lg-5 py-4 py-lg-5">
                    <div class="row justify-content-between mt-lg-2">
                        <div class="col-12 col-lg-6 pl-lg-5">
                            <h1 class="mb-3" data-element="topic-name">{{ $title }}</h1>
                            @if($subtitle)
                                <p class="text-secondary mb-5">{{ $subtitle }}</p>
                            @endif
                            @if($content)
                                <p class="mb-5">{!! $content !!}</p>
                            @endif
                        </div>

                        @if(!empty($managing_offices))
                        <div class="col-12 col-lg-5">
                            <h3 class="title-xsmall-semi-bold text-secondary mb-3">Questo argomento è gestito da:</h3>
                            @foreach($managing_offices as $office)
                            <div class="card card-teaser shadow-sm rounded p-3 mb-2">
                                <div class="card-body">
                                    <h4 class="mb-1">
                                        <a href="{{ $office['url'] ?? '#' }}" class="text-decoration-none">
                                            {{ $office['title'] ?? 'Ufficio' }}
                                        </a>
                                    </h4>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>