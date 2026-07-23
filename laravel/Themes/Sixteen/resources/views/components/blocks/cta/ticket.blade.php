@props([
    'cta' => [],
])

@if ($cta !== [])
    <div class="cmp-text-button mt-0">
        <h2 class="title-xxlarge mb-0">{{ $cta['title'] ?? '' }}</h2>
        @if (! empty($cta['text']))
            <div class="text-wrapper">
                <p class="subtitle-small mb-3 mt-3">{{ $cta['text'] }}</p>
            </div>
        @endif
        @if (! empty($cta['button_text']))
            <div class="button-wrapper">
                <a
                    href="{{ $cta['button_url'] ?? '/it/tests/ticket-crea' }}"
                    class="btn btn-primary mobile-full py-3 mt-2 mb-4 mb-lg-0"
                >
                    <span>{{ $cta['button_text'] }}</span>
                </a>
            </div>
        @endif
    </div>
@endif
