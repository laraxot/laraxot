@props(['data' => []])
@php
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $primaryCtaLabel = $data['primary_cta_label'] ?? '';
    $primaryCtaUrl = $data['primary_cta_url'] ?? '#';
    $secondaryCtaLabel = $data['secondary_cta_label'] ?? '';
    $secondaryCtaUrl = $data['secondary_cta_url'] ?? '#';
    $image = $data['image'] ?? '';
    $stats = $data['stats'] ?? [];
@endphp
<section class="hero-fullscreen position-relative overflow-hidden text-white" style="min-height: 80vh; background: linear-gradient(135deg, #1a365d 0%, #2d4a7a 100%);">
    @if ($image)
        <div class="position-absolute w-100 h-100 opacity-25" style="background-image: url('{{ $image }}'); background-size: cover; background-position: center;"></div>
    @endif
    <div class="container position-relative h-100 d-flex align-items-center" style="min-height: 80vh;">
        <div class="row w-100">
            <div class="col-lg-8 mx-auto text-center">
                @if ($title)
                    <h1 class="display-3 fw-bold mb-4">{{ $title }}</h1>
                @endif
                @if ($subtitle)
                    <p class="lead mb-5 fs-4">{{ $subtitle }}</p>
                @endif
                <div class="hero-cta d-flex gap-3 justify-content-center flex-wrap mb-5">
                    @if ($primaryCtaLabel)
                        <a href="{{ $primaryCtaUrl }}" class="btn btn-light btn-lg px-5">{{ $primaryCtaLabel }}</a>
                    @endif
                    @if ($secondaryCtaLabel)
                        <a href="{{ $secondaryCtaUrl }}" class="btn btn-outline-light btn-lg px-5">{{ $secondaryCtaLabel }}</a>
                    @endif
                </div>
                @if (count($stats) > 0)
                    <div class="row g-4 mt-5">
                        @foreach ($stats as $stat)
                            <div class="col-md-4">
                                <div class="stat-item p-3 rounded bg-white bg-opacity-10">
                                    <div class="stat-value fs-2 fw-bold">{{ $stat['value'] ?? '' }}</div>
                                    <div class="stat-label small opacity-75">{{ $stat['label'] ?? '' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
