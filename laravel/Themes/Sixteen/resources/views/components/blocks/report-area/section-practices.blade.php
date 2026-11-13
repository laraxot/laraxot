{{--
    Sezione con filtro, ricerca e accordion di pratiche (JSON: section.id, class, title, slug (suffisso id), filter_label, sort_label, sort_items[],
    search_label, search_placeholder, items[n, title, status{icon, alt, label, class}, date, code, ...], more, more_padded).
--}}
<section class="it-page-section {{ $section['class'] ?? '' }}" id="{{ $section['id'] ?? '' }}">
    <div class="cmp-filter">
        <div class="filter-section">
            <h2 class="cmp-filter__title title-xxlarge">{{ $section['title'] ?? '' }}</h2>
            <div class="filter-wrapper d-flex align-items-center">
                <button type="button" class="btn p-0 pe-2 t-primary">
                    <span class="rounded-icon"><svg class="icon icon-primary icon-xs me-1" aria-hidden="true"><use href="{{ $icon('it-funnel') }}"></use></svg></span>
                    <span class="">{{ $section['filter_label'] ?? '' }}</span></button>
                <div class="dropdown">
                    <button class="btn btn-dropdown dropdown-toggle" type="button" id="dropdownMenu-{{ $section['slug'] ?? '' }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="">
                        <svg class="icon-expand icon icon-sm icon-primary"><use href="{{ $icon('it-expand') }}"></use></svg>
                        <span class="dropdown__title">{{ $section['sort_label'] ?? '' }}</span></button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu-{{ $section['slug'] ?? '' }}">
                        <div class="link-list-wrapper">
                            <ul class="link-list">
                                @foreach ($section['sort_items'] ?? [] as $sort)
                                    <li><a class="dropdown-item list-item" href="#"><span>{{ $sort }}</span></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="cmp-input-search">
            <div class="form-group autocomplete-wrapper">
                <div class="input-group">
                    <label for="autocomplete-{{ $section['slug'] ?? '' }}" class="visually-hidden">{{ $section['search_label'] ?? '' }}</label>
                    <input type="search" class="autocomplete form-control" placeholder="{{ $section['search_placeholder'] ?? '' }}" id="autocomplete-{{ $section['slug'] ?? '' }}" name="{{ $section['slug'] ?? '' }}" data-bs-autocomplete="[]">
                    <span class="autocomplete-icon" aria-hidden="true"><svg class="icon icon-sm"><use href="{{ $icon('it-search') }}"></use></svg></span>
                </div>
            </div>
        </div>
    </div>
    <div class="cmp-accordion">
        <div class="accordion" id="">
            @foreach ($section['items'] ?? [] as $item)
                <div class="accordion-item">
                    <div class="accordion-header" id="heading{{ $item['n'] ?? '' }}">
                        <button class="accordion-button collapsed title-snall-semi-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $item['n'] ?? '' }}" aria-expanded="false" aria-controls="collapse{{ $item['n'] ?? '' }}">
                            <div class="button-wrapper"> {{ $item['title'] ?? '' }}
                                <div class="icon-wrapper">
                                    <img class="icon-folder" src="{{ $assets }}/images/{{ $item['status']['icon'] ?? '' }}" alt="{{ $item['status']['alt'] ?? '' }}" role="img">
                                    <span class="{{ $item['status']['class'] ?? '' }}">{{ $item['status']['label'] ?? '' }}</span>
                                </div>
                            </div>
                        </button>
                        <p class="accordion-date title-xsmall-regular mb-0">{{ $item['date'] ?? '' }}</p>
                    </div>
                    <div id="collapse{{ $item['n'] ?? '' }}" class="accordion-collapse collapse p-0" data-bs-parent="#accordionExample{{ $item['n'] ?? '' }}" role="region" aria-labelledby="heading{{ $item['n'] ?? '' }}">
                        <div class="accordion-body">
                            <p class="mb-2 fw-normal">{{ $section['code_label'] ?? '' }} <span class="label">{{ $item['code'] ?? '' }}</span></p>
                            <a href="#" class="mb-2"><span class="t-primary">{{ $section['service_label'] ?? '' }}</span></a>
                            <a class="chip chip-simple" href="#"><span class="chip-label">{{ $section['chip_label'] ?? '' }}</span></a>
                            <div class="cmp-icon-list">
                                <div class="link-list-wrapper">
                                    <ul class="link-list">
                                        <li class="shadow mt-3">
                                            <a class="list-item icon-left t-primary title-small-semi-bold" href="#" aria-label="{{ sprintf($section['attachment_aria'] ?? '%s', $section['attachment_label'] ?? '') }}">
                                                <span class="list-item-title-icon-wrapper">
                                                    <svg class="icon icon-sm align-self-start icon-color" aria-hidden="true"><use href="{{ $icon('it-clip') }}"></use></svg>
                                                    <span class="list-item-title title-small-semi-bold">{{ $section['attachment_label'] ?? '' }}</span>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary justify-content-center my-3"><span class="">{{ $section['action_label'] ?? '' }}</span></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <button type="button" @class(['btn accordion-view-more mb-2', 'pt-3' => $section['more_padded'] ?? false, 't-primary title-xsmall-semi-bold ps-lg-3'])><span class="">{{ $section['more'] ?? '' }}</span></button>
</section>
