@props(['section' => []])
{{--
    Sezione "news-cards": card notizia (immagine opzionale, categoria, data, titolo, testo) in griglia, con ricerca
    (`search`: label, placeholder, button, status, muted) e paginazione (`pagination`) opzionali. `style` = grey (fascia
    grigia) o plain; `heading` = xxlarge o secondary. Le immagini sono file di public/design-comuni/assets/images.
--}}
@php
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $grey = ($section['style'] ?? 'plain') === 'grey';
    $headings = ['xxlarge' => 'title-xxlarge mb-4', 'secondary' => 'text-secondary mb-4'];
    $headingClass = $headings[$section['heading'] ?? 'xxlarge'] ?? $headings['xxlarge'];
    $search = $section['search'] ?? null;
    $pagination = $section['pagination'] ?? null;
@endphp
@if ($grey)<div class="bg-grey-card py-5">@endif
<div @class(['container', 'py-5' => ! $grey])>
    <h2 class="{{ $headingClass }}">{{ $section['title'] ?? '' }}</h2>
    @if (filled($search))
        <div class="cmp-input-search">
            <div class="form-group autocomplete-wrapper mb-0">
                <div class="input-group">
                    <label for="autocomplete-autocomplete-three" class="visually-hidden">{{ $search['label'] ?? '' }}</label>
                    <input type="search" class="autocomplete form-control" placeholder="{{ $search['placeholder'] ?? '' }}" id="autocomplete-autocomplete-three" name="autocomplete-three" data-bs-autocomplete="[]">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="button-3">{{ $search['button'] ?? '' }}</button>
                    </div>
                    <span class="autocomplete-icon" aria-hidden="true">
                        <svg class="icon icon-sm icon-primary"><use href="{{ $icon('it-search') }}"></use></svg></span>
                </div>
                <p id="autocomplete-label" @class(['u-grey-light' => (bool) ($search['muted'] ?? false), 'text-paragraph-card mt-2 mb-30 mt-lg-3 mb-lg-40'])>{{ $search['status'] ?? '' }}</p>
            </div>
        </div>
    @endif
    <div class="row g-4">
        @foreach ($section['items'] ?? [] as $item)
            <div class="col-md-6 col-xl-4">
                <div class="card-wrapper border border-light rounded shadow-sm">
                    <div class="card no-after rounded">
                        @if (filled($item['image'] ?? null))
                            <div class="img-responsive-wrapper">
                                <div class="img-responsive img-responsive-panoramic">
                                    <figure class="img-wrapper">
                                        <img class="" src="{{ $assets }}/images/{{ $item['image']['file'] ?? '' }}" title="{{ $item['image']['title'] ?? '' }}" alt="{{ $item['image']['alt'] ?? '' }}"></figure>
                                </div>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="category-top">
                                <a class="category text-decoration-none" href="{{ $item['category']['url'] ?? '#' }}">{{ $item['category']['label'] ?? '' }}</a>
                                <span class="data">{{ $item['date'] ?? '' }}</span>
                            </div>
                            <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none" data-element="news-category-link">
                                <h3 class="card-title">{{ $item['title'] ?? '' }}</h3></a>
                            <p class="card-text text-secondary">{{ $item['text'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if (filled($pagination))
        <div class="row my-4">
            <nav class="pagination-wrapper justify-content-center" aria-label="{{ $pagination['aria_label'] ?? '' }}">
                <ul class="pagination">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-hidden="true">
                            <svg class="icon icon-primary"><use href="{{ $icon('it-chevron-left') }}"></use></svg>
                            <span class="visually-hidden">{{ $pagination['previous'] ?? '' }}</span></a>
                    </li>
                    @foreach ($pagination['pages'] ?? [] as $page)
                        <li class="page-item">
                            @if ($loop->index === ($pagination['current'] ?? 0))
                                <a class="page-link" href="#" aria-current="page">
                                    <span class="d-inline-block d-sm-none">{{ $pagination['page_label'] ?? '' }} </span>{{ $page }} </a>
                            @else
                                <a class="page-link" href="#">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                    <li class="page-item">
                        <a class="page-link" href="#">
                            <span class="visually-hidden">{{ $pagination['next'] ?? '' }}</span>
                            <svg class="icon icon-primary"><use href="{{ $icon('it-chevron-right') }}"></use></svg></a>
                    </li>
                </ul>
            </nav>
        </div>
    @endif
</div>
@if ($grey)</div>@endif
