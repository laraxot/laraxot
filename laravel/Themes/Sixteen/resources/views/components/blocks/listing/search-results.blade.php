@props([
    'title' => '',
    'button_label' => __('pub_theme::news.index.search_submit'),
    'summary' => '',
    'items' => [],
    'filters' => [],
    'results' => [],
    'pagination' => [],
    'rating' => null,
    'contacts' => null,
])

@php
    // Additional props to support new data structure
    $filters = $filters ?? [];
    $results = $results ?? [];
    $pagination = $pagination ?? [];
    $rating = $rating ?? null;
    $contacts = $contacts ?? null;
@endphp

<div class="search-results-page bg-grey-card py-5">
    <div class="dc-search-layout container mx-auto px-4" style="max-width:1370px; display:grid; grid-template-columns:minmax(0,3fr) minmax(0,8fr); gap:2rem;">

        <!-- Filters sidebar -->
        <aside class="dc-search-filters scroll-filter-wrapper d-none d-lg-block">
            <div class="bg-white rounded p-4 shadow-sm">
                <h3 class="title-medium-2-semi-bold mb-3">{{ __('pub_theme::components_search.filters.to_apply_title.label') }}</h3>
                <fieldset>
                    <legend class="h6 text-uppercase category-list__title">{{ __('pub_theme::components_common.fields.types.label') }}</legend>
                    <div class="categoy-list pb-4">
                        <ul class="list-none m-0">
                            @foreach($filters as $filter)
                                <li>
                                    <div class="checkbox-body border-light py-1 mb-1">
                                        <input type="checkbox" id="{{ $filter['id'] ?? '' }}" name="category" value="{{ $filter['value'] ?? '' }}" aria-label="{{ $filter['label'] ?? '' }}">
                                        <label for="{{ $filter['id'] ?? '' }}" class="subtitle-small_semi-bold mb-0 category-list__list">{{ $filter['label'] ?? '' }}</label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </fieldset>
            </div>
        </aside>

        <!-- Main content -->
        <main class="dc-search-content">
            <h2 class="title-xxlarge mb-4">{{ $title }}</h2>

            <!-- Search Input -->
            <div class="cmp-input-search">
                <div class="form-group autocomplete-wrapper mb-0">
                    <div class="input-group">
                        <label for="search-input" class="visually-hidden">{{ $title }}</label>
                        <input type="search" class="form-control" placeholder="{{ __('pub_theme::components_search.fields.keyword_search.placeholder') }}" id="search-input" name="search">
                        <div class="input-group-append">
                            <button class="btn btn-primary" style="background-color:#007A52;color:#fff;border-color:#007A52;" type="button">{{ $button_label }}</button>
                        </div>
                        <span class="autocomplete-icon" aria-hidden="true">
                            <svg class="icon icon-sm icon-primary">
                                <use href="sprites.svg#it-search"></use>
                            </svg>
                        </span>
                    </div>
                    @if($summary !== '')
                        <p id="search-summary" class="u-grey-light text-paragraph-card mt-2 mb-30 mt-lg-3 mb-lg-40">{{ $summary }}</p>
                    @endif
                </div>
            </div>

            <!-- Search Results -->
            @if(!empty($results))
                <div class="row g-4 mt-3">
                    @foreach($results as $result)
                        <div class="col-md-6 col-xl-4">
                            <div class="card-wrapper border border-light rounded shadow-sm">
                                <article class="card card-teaser card-teaser-image-top no-after rounded h-100">
                                    @if(!empty($result['image']))
                                        <div class="img-responsive-wrapper">
                                            <div class="img-responsive img-responsive-panoramic">
                                                <figure class="img-wrapper">
                                                    <img class="img-fluid" src="{{ $result['image'] }}" alt="{{ $result['image_alt'] ?? 'descrizione immagine' }}">
                                                </figure>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="card-body">
                                        @if(!empty($result['category']) || !empty($result['date']))
                                            <div class="category-top">
                                                @if(!empty($result['category']))
                                                    <a class="category text-decoration-none" href="#">{{ $result['category'] }}</a>
                                                @endif
                                                @if(!empty($result['date']))
                                                    <span class="data">{{ $result['date'] }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        <h3 class="card-title">{{ $result['title'] ?? '' }}</h3>

                                        @if(!empty($result['description']))
                                            <p class="card-text text-secondary">{{ $result['description'] }}</p>
                                        @endif
                                    </div>
                                </article>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Pagination -->
            @if(!empty($pagination))
                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        @foreach($pagination as $page)
                            <li class="page-item {{ $page['active'] ?? false ? 'active' : '' }}">
                                <a class="page-link" href="{{ $page['url'] ?? '#' }}">{{ $page['label'] ?? '' }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            <!-- Rating Component -->
            @if($rating)
                <div class="cmp-rating pt-lg-80 pb-lg-80" id="rating">
                    <div class="card shadow card-wrapper" data-element="feedback">
                        <div class="cmp-rating__card-first">
                            <div class="card-header border-0">
                                <h2 class="title-medium-2-semi-bold mb-0" data-element="feedback-title">{{ $rating['title'] ?? '' }}</h2>
                            </div>
                            <div class="card-body">
                                <div class="rating-stars">
                                    @for($i = 5; $i >= 1; $i--)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="ratingA" id="star{{ $i }}a" value="{{ $i }}" {{ $i == ($rating['selected'] ?? null) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="star{{ $i }}a">
                                                @for($j = 1; $j <= $i; $j++)
                                                    <svg class="icon icon-sm icon-primary" aria-hidden="true">
                                                        <use href="sprites.svg#it-star-full"></use>
                                                    </svg>
                                                @endfor
                                                @for($j = $i + 1; $j <= 5; $j++)
                                                    <svg class="icon icon-sm icon-secondary" aria-hidden="true">
                                                        <use href="sprites.svg#it-star-outline"></use>
                                                    </svg>
                                                @endfor
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="cmp-rating__card-second d-none" data-step="3">
                            <div class="card-header border-0 mb-0">
                                <h2 class="title-medium-2-bold mb-0">{{ $rating['success_message'] ?? '' }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Contact Section -->
            @if($contacts)
                <div class="cmp-contacts mt-5">
                    <div class="card w-100">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold">{{ __('pub_theme::navigation.homepage.contacts') }}</h2>
                            <ul class="contact-list p-0">
                                @if(!empty($contacts['address']))
                                    <li class="contact-item">
                                        <strong>{{ __('pub_theme::components_common.form.address_colon.label') }}</strong> {{ $contacts['address'] }}
                                    </li>
                                @endif
                                @if(!empty($contacts['phone']))
                                    <li class="contact-item">
                                        <strong>{{ __('pub_theme::components_common.form.phone_colon.label') }}</strong> {{ $contacts['phone'] }}
                                    </li>
                                @endif
                                @if(!empty($contacts['email']))
                                    <li class="contact-item">
                                        <strong>{{ __('pub_theme::components_common.form.email_colon.label') }}</strong> <a href="mailto:{{ $contacts['email'] }}">{{ $contacts['email'] }}</a>
                                    </li>
                                @endif
                                @if(!empty($contacts['urp_office']))
                                    <li class="contact-item">
                                        <strong>{{ __('pub_theme::components_footer.contacts.urp_short.label') }}</strong> {{ $contacts['urp_office'] }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>