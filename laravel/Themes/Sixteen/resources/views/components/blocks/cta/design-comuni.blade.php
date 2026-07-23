@props(['data' => []])

@php
    $resolve = static function (mixed $value): string {
        if (empty($value)) {
            return '';
        }

        return str_contains((string) $value, '::') ? __((string) $value) : (string) $value;
    };

    $title = $resolve($data['title'] ?? '');
    $text = $resolve($data['text'] ?? $data['description'] ?? '');
    $buttonText = $resolve($data['button_text'] ?? '');
    $buttonUrl = $data['button_url'] ?? '#';
    $useModal = (bool) ($data['use_modal'] ?? true);
@endphp

@if ($title !== '')
    <section class="container my-4 my-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="cmp-text-button mt-0">
                    <h2 class="title-xxlarge mb-0">{{ $title }}</h2>
                    @if ($text !== '')
                        <div class="text-wrapper">
                            <p class="subtitle-small mb-3 mt-3">{{ $text }}</p>
                        </div>
                    @endif
                    @if ($buttonText !== '')
                        <div class="button-wrapper">
                            @if ($useModal)
                                <button
                                    type="button"
                                    class="btn btn-primary mobile-full py-3 mt-2 mb-4 mb-lg-0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-disservizio"
                                >
                                    <span>{{ $buttonText }}</span>
                                </button>
                            @else
                                <a href="{{ $buttonUrl }}" class="btn btn-primary mobile-full py-3 mt-2 mb-4 mb-lg-0">
                                    <span>{{ $buttonText }}</span>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
