@props([
    'title' => '',
    'button_label' => 'Invio',
    'summary' => '',
    'items' => [],
])

<div class="bg-grey-card py-5">
    <div class="container">
        <h2 class="title-xxlarge mb-4">{{ $title }}</h2>
        <div class="cmp-input-search">
            <div class="form-group autocomplete-wrapper mb-0">
                <div class="input-group">
                    <label for="search-input" class="visually-hidden">{{ $title }}</label>
                    <input type="search" class="form-control" placeholder="Cerca per parola chiave" id="search-input" name="search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">{{ $button_label }}</button>
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

        <div class="row g-4">
            @foreach($items as $item)
                <div class="col-md-6 col-xl-4">
                    <div class="card-wrapper border border-light rounded shadow-sm">
                        <article class="card card-teaser card-teaser-image-top no-after rounded h-100">
                            @if(!empty($item['image']))
                                <div class="img-responsive-wrapper">
                                    <div class="img-responsive img-responsive-panoramic">
                                        <figure class="img-wrapper">
                                            <img class="img-fluid" src="{{ $item['image'] }}" alt="{{ $item['image_alt'] ?? 'descrizione immagine' }}">
                                        </figure>
                                    </div>
                                </div>
                            @endif

                            <div class="card-body">
                                @if(!empty($item['category']) || !empty($item['date']))
                                    <div class="category-top">
                                        @if(!empty($item['category']))
                                            <a class="category text-decoration-none" href="#">{{ $item['category'] }}</a>
                                        @endif
                                        @if(!empty($item['date']))
                                            <span class="data">{{ $item['date'] }}</span>
                                        @endif
                                    </div>
                                @endif

                                <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none" data-element="news-category-link">
                                    <h3 class="card-title">{{ $item['title'] ?? '' }}</h3>
                                </a>

                                @if(!empty($item['description']))
                                    <p class="card-text text-secondary">{{ $item['description'] }}</p>
                                @endif
                            </div>
                        </article>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
