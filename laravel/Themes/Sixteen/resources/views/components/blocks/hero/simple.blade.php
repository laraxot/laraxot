@props(['data' => []])
@php
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $primaryCtaLabel = $data['primary_cta_label'] ?? '';
    $primaryCtaUrl = $data['primary_cta_url'] ?? '#';
    $secondaryCtaLabel = $data['secondary_cta_label'] ?? '';
    $secondaryCtaUrl = $data['secondary_cta_url'] ?? '#';
    $image = $data['image'] ?? '';

    if ($image === '/images/hero-bg.jpg') {
        $image = asset('themes/Two/images/hero-bg.jpg');
    }
@endphp
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                @if ($title)
                    <h1 class="hero-title display-4 fw-bold mb-4">{{ $title }}</h1>
                @endif
                @if ($subtitle)
                    <p class="hero-subtitle lead mb-4">{{ $subtitle }}</p>
                @endif
                <div class="hero-cta d-flex gap-3 flex-wrap">
                    @if ($primaryCtaLabel)
                        <a href="{{ $primaryCtaUrl }}" class="btn btn-primary btn-lg">{{ $primaryCtaLabel }}</a>
                    @endif
                    @if ($secondaryCtaLabel)
                        <a href="{{ $secondaryCtaUrl }}" class="btn btn-outline-primary btn-lg">{{ $secondaryCtaLabel }}</a>
                    @endif
                </div>
            </div>
            @if ($image)
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <img src="{{ $image }}" alt="" class="img-fluid rounded shadow" loading="lazy">
                </div>
            @endif
        </div>
    </div>
</section>
