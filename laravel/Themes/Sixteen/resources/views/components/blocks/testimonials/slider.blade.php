@props(['data' => []])
@php
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $testimonials = $data['testimonials'] ?? [];
    $sectionId = $data['id'] ?? '';
@endphp
<section @if($sectionId) id="{{ $sectionId }}" @endif class="testimonials-section py-5">
    <div class="container">
        @if ($title)
            <h2 class="text-center fw-bold mb-3">{{ $title }}</h2>
        @endif
        @if ($subtitle)
            <p class="text-center text-muted mb-5">{{ $subtitle }}</p>
        @endif
        @if (count($testimonials) > 0)
            <div class="row g-4">
                @foreach ($testimonials as $testimonial)
                    <div class="col-md-6 col-lg-3">
                        <div class="testimonial-card h-100 border rounded p-4 shadow-sm d-flex flex-column">
                            <div class="testimonial-content flex-grow-1">
                                <p class="fst-italic mb-4">"{{ $testimonial['content'] ?? '' }}"</p>
                            </div>
                            <div class="testimonial-footer d-flex align-items-center gap-3 mt-auto pt-3 border-top">
                                @if (isset($testimonial['image']))
                                    <img src="{{ $testimonial['image'] }}" alt="" class="rounded-circle" width="48" height="48" loading="lazy">
                                @endif
                                <div>
                                    <div class="fw-semibold small">{{ $testimonial['name'] ?? '' }}</div>
                                    <div class="text-muted small">{{ $testimonial['role'] ?? '' }}</div>
                                    @if (isset($testimonial['location']))
                                        <div class="text-muted small">{{ $testimonial['location'] }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
