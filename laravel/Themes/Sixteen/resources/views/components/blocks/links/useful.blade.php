{{--
    Useful Links Section
    Reference: design-comuni-pagine-statiche/src/pages/sito/homepage.hbs .useful-links-section
--}}
@props([
    'search_placeholder' => 'Cerca una parola chiave',
    'links_heading'      => 'Link utili',
    'items'              => [],
])

<section class="useful-links-section">
    <div class="section section-muted p-0 py-5">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-lg-6">

                    {{-- Campo di ricerca --}}
                    <div class="mb-2 mb-lg-4">
                        <div class="form-group">
                            <label for="autocomplete-three" class="form-label">{{ $search_placeholder }}</label>
                            <input
                                type="search"
                                id="autocomplete-three"
                                placeholder="{{ $search_placeholder }}"
                                class="form-control"
                                aria-label="{{ $search_placeholder }}"
                            />
                        </div>
                    </div>

                    {{-- Link utili --}}
                    <div class="link-list-wrapper">
                        <div class="link-list-heading text-uppercase mb-3 ps-0 text-secondary">{{ $links_heading }}</div>
                        <ul class="link-list">
                            @foreach($items as $item)
                            <li>
                                <a class="list-item mb-3 active ps-0" href="{{ $item['url'] ?? '#' }}">
                                    <span class="text-button-normal">{{ $item['label'] ?? '' }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
