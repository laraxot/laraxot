@props(['data' => []])
@php
    $title = $data['title'] ?? '';
    $description = $data['description'] ?? '';
    $placeholder = $data['placeholder'] ?? __('Your email');
    $buttonLabel = $data['button_label'] ?? __('Subscribe');
    $privacyText = $data['privacy_text'] ?? '';
    $bgColor = $data['bg_color'] ?? 'brand-blue';
    $subscribeUrl = \Illuminate\Support\Facades\Route::has('newsletter.subscribe')
        ? route('newsletter.subscribe')
        : null;
@endphp
<section class="newsletter-section py-5" style="background-color: var(--bs-{{ $bgColor }}, #0d6efd);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center text-white">
                @if ($title)
                    <h2 class="fw-bold mb-3">{{ $title }}</h2>
                @endif
                @if ($description)
                    <p class="mb-4 opacity-90">{{ $description }}</p>
                @endif
                @if ($subscribeUrl)
                    <form class="row g-2 justify-content-center" method="POST" action="{{ $subscribeUrl }}">
                        @csrf
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="{{ $placeholder }}" required>
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-light btn-lg px-4">{{ $buttonLabel }}</button>
                        </div>
                    </form>
                @endif
                @if ($privacyText)
                    <p class="small mt-3 opacity-75">{{ $privacyText }}</p>
                @endif
            </div>
        </div>
    </div>
</section>
