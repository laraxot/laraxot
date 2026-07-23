{{--
    Search + Useful Links Section
    Reference: design-comuni-pagine-statiche/sito/homepage.html .useful-links-section
--}}
@php
    $data = $data ?? [];
    $placeholder  = isset($placeholder) ? $placeholder : ($data['placeholder'] ?? 'Cerca una parola chiave');
    $action       = isset($action) ? $action : ($data['action'] ?? '/it/ricerca');
    $useful_links = isset($useful_links) && is_array($useful_links) ? $useful_links : ($data['useful_links'] ?? []);
@endphp

<section class="useful-links-section">
    <div class="section section-muted p-0 py-5">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="cmp-input-search">
                        <div class="form-group autocomplete-wrapper mb-2 mb-lg-4">
                            <div class="input-group">
                                <label for="autocomplete-autocomplete-three" class="visually-hidden">{{ $placeholder }}</label>
                                <input type="search" class="autocomplete form-control" placeholder="{{ $placeholder }}" id="autocomplete-autocomplete-three" name="autocomplete-three" data-bs-autocomplete="[]">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="button-3">Invio</button>
                                </div>
                                <span class="autocomplete-icon" aria-hidden="true">
                                    <svg class="icon icon-sm icon-primary">
                                        <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-search') }}"></use>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    @if(!empty($useful_links))
                    <div class="link-list-wrapper">
                        <div class="link-list-heading text-uppercase mb-3 ps-0 text-secondary">Link utili</div>
                        <ul class="link-list">
                            @foreach($useful_links as $link)
                            <li>
                                <a class="list-item mb-3 active ps-0" href="{{ $link['url'] ?? '#' }}">
                                    <span class="text-button-normal">{{ $link['label'] }}</span>
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
