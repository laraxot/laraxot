@props(['data' => []])

@php
    $ns = 'fixcity::ticket';
    $title = $data['title'] ?? __($ns . '.map.cta.title.label');
    $text = $data['text'] ?? __($ns . '.map.cta.text.label');
    $buttonText = $data['button_text'] ?? __($ns . '.map.cta.button.label');
    $buttonUrl = $data['button_url'] ?? '/it/tests/ticket-crea';
@endphp

@if($title)
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 mt-50 mb-4 mb-lg-0">
            <div class="cmp-text-button mt-0">
                <h2 class="title-xxlarge mb-0">{{ $title }}</h2>
                @if($text)
                <div class="text-wrapper">
                    <p class="subtitle-small mb-3 mt-3">{{ $text }}</p>
                </div>
                @endif
                <div class="button-wrapper">
                    <a
                        href="{{ $buttonUrl }}"
                        class="btn btn-primary mobile-full py-3 mt-2 mb-4 mb-lg-0"
                    >
                        <span>{{ $buttonText }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
