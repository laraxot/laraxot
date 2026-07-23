{{--
    Hero Argomento - Bootstrap Italia it-hero-wrapper with card overlay
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/argomento.html
    Used for: single topic detail pages
--}}
@props(['data' => []])

@php
    $title = $data['title'] ?? 'Argomento';
    $subtitle = $data['subtitle'] ?? 'Informazioni e servizi correlati';
    $description = $data['content'] ?? '';
    $image = $data['image'] ?? 'https://picsum.photos/800/400';
    $office = $data['office'] ?? null;
    $offices = $data['offices'] ?? [];
@endphp

<div class="it-hero-wrapper it-wrapped-container" id="main-container">
    {{-- Hero Image --}}
    <div class="img-responsive-wrapper">
        <div class="img-responsive">
            <div class="img-wrapper">
                <img src="{{ $image }}" alt="{{ $title }}" title="{{ $title }}">
            </div>
        </div>
    </div>

    {{-- Hero Card with overlapping bottom --}}
    <div class="container">
        <div class="row">
            <div class="col-12 drop-shadow">
                <div class="it-hero-card it-hero-bottom-overlapping rounded px-lg-5 py-4 py-lg-5">
                    {{-- Title + Description --}}
                    <div class="row justify-content-between mt-lg-2">
                        <div class="col-12 col-lg-5 pl-lg-5">
                            <h1 class="mb-3" data-element="topic-name">{{ $title }}</h1>
                            @if($subtitle || $description)
                            <p class="text-secondary mb-5">{{ $subtitle }}{{ $description }}</p>
                            @endif
                        </div>

                        {{-- Office Cards --}}
                        @if($offices || $office)
                        <div class="col-12 col-lg-5">
                            <h3 class="title-xsmall-semi-bold text-secondary mb-2">Questo argomento è gestito da:</h3>
                            @php
                                $officeList = $offices ?: ($office ? [$office] : []);
                            @endphp
                            @foreach($officeList as $idx => $off)
                            <div class="card card-teaser {{ $off['type'] ?? 'card-teaser-info' }} shadow-sm rounded d-flex align-items-center {{ $idx > 0 ? 'mt-2 mt-lg-0' : '' }} p-3">
                                <div class="card-body">
                                    <h4 class="mb-1 title-small-semi-bold-medium">
                                        <a href="{{ $off['url'] ?? '#' }}" class="text-decoration-none">
                                            {{ $off['name'] ?? $off['title'] ?? 'Ufficio' }}
                                        </a>
                                    </h4>
                                    @if($off['address'] ?? false)
                                    <div class="card-text">
                                        <p class="u-main-black">{{ $off['address'] }}</p>
                                    </div>
                                    @endif
                                    @if($off['phone'] ?? false)
                                    <div class="card-text">
                                        <p class="u-main-black">
                                            <svg class="icon icon-xs"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-phone"></use></svg>
                                            {{ $off['phone'] }}
                                        </p>
                                    </div>
                                    @endif
                                    @if($off['email'] ?? false)
                                    <div class="card-text">
                                        <p class="u-main-black">
                                            <svg class="icon icon-xs"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-mail"></use></svg>
                                            <a href="mailto:{{ $off['email'] }}" class="text-decoration-none">{{ $off['email'] }}</a>
                                        </p>
                                    </div>
                                    @endif
                                </div>
                                @if($off['image'] ?? false)
                                <div class="avatar size-xl">
                                    <img src="{{ $off['image'] }}" alt="Immagine">
                                </div>
                                @endif
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
