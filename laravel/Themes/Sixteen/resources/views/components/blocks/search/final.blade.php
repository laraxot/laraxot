@props(['data' => []])
@php
    $title = $data['title'] ?? 'Cerca';
    $placeholder = $data['placeholder'] ?? 'Cerca nel sito';
    $button_label = $data['button_label'] ?? 'Cerca';
    $action = $data['action'] ?? '#';
    $suggestions_title = $data['suggestions_title'] ?? 'FORSE STAVI CERCANDO';
    $suggestions = $data['suggestions'] ?? [];
@endphp

<section class="section section-muted">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6">
                <div class="cmp-input-search">
                    <h2>{{ $title }}</h2>
                    <form action="{{ $action }}" method="get">
                        <div class="form-group autocomplete-wrapper">
                            <div class="input-group">
                                <label class="visually-hidden" for="homepage-final-search">{{ $placeholder }}</label>
                                <input
                                    id="homepage-final-search"
                                    type="search"
                                    class="autocomplete form-control"
                                    name="q"
                                    placeholder="{{ $placeholder }}"
                                    data-bs-autocomplete="[]"
                                >
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">{{ $button_label }}</button>
                                </div>
                                <span class="autocomplete-icon" aria-hidden="true">
                                    <svg class="icon icon-sm icon-primary">
                                        <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-search') }}"></use>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </form>

                    @if(!empty($suggestions))
                        <div class="link-list-wrapper mt-4">
                            <div class="link-list-heading text-uppercase mb-3 ps-0 text-secondary">{{ $suggestions_title }}</div>
                            <ul class="link-list">
                                @foreach($suggestions as $suggestion)
                                    <li>
                                        <a class="list-item mb-3 active ps-0" href="{{ $suggestion['url'] ?? '#' }}">
                                            <span class="text-button-normal">{{ $suggestion['label'] ?? '' }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
