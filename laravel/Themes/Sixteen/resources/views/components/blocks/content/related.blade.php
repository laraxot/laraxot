@props([
    'data' => [],
    'title' => 'Contenuti correlati',
    'categories' => [],
    'contacts' => [],
])

@php
    $blockData = is_array($data) ? $data : [];
    $title = $blockData['title'] ?? $title;
    $categories = $blockData['categories'] ?? $categories;
    $contacts = $blockData['contacts'] ?? $contacts;

    $sprite = '/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg';
    $contactsTitle = $contacts['title'] ?? '';
    $contactsLinks = $contacts['links'] ?? [];
@endphp

<div class="bg-grey-card shadow-contacts">
    <div class="container">
        <div class="row">
            <div class="col-12 pt-5">
                @if ($categories !== [])
                    <div class="it-carousel-wrapper carousel-4-card splide cmp-carousel" data-bs-carousel-splide>
                        <div class="it-header-block">
                            <div class="it-header-block-title">
                                <h2 class="text-center border-0 cmp-carousel__title pb-0">{{ $title }}</h2>
                            </div>
                        </div>
                        <div class="splide__track">
                            <ul class="splide__list it-carousel-all">
                                @foreach ($categories as $category)
                                    @php
                                        $links = $category['links'] ?? [];
                                        $visibleLinks = array_slice($links, 0, 4);
                                        $collapsedLinks = array_slice($links, 4);
                                        $collapseId = $loop->last ? 'collapseExample' : 'collapseExample'.$loop->index;
                                    @endphp
                                    <li class="splide__slide">
                                        <div class="card-wrapper card-space h-100 pb-4">
                                            <div class="card card-bg single-card rounded shadow-sm">
                                                <div class="cmp-carousel__header">
                                                    <svg class="icon icon-md" aria-hidden="true">
                                                        <use href="{{ $sprite }}#{{ $category['icon'] ?? 'it-file' }}"></use>
                                                    </svg>
                                                    <span class="ms-3 cmp-carousel__header-title">{{ $category['title'] ?? '' }}</span>
                                                </div>
                                                <div class="card-body">
                                                    <div class="link-list-wrapper">
                                                        <ul class="card-body__list">
                                                            @foreach ($visibleLinks as $link)
                                                                <li>
                                                                    <a class="list-item px-0" href="{{ $link['url'] ?? '#' }}"><span>{{ $link['label'] ?? '' }}</span></a>
                                                                </li>
                                                            @endforeach
                                                            @if ($collapsedLinks !== [])
                                                                <li x-data="{ open: false }">
                                                                    <a class="show-more px-0" data-bs-toggle="collapse" href="#{{ $collapseId }}" aria-controls="{{ $collapseId }}" :aria-expanded="open.toString()" @click.prevent="open = !open">
                                                                        <span class="show-more d-flex align-items-center">Vedi altri {{ count($collapsedLinks) }}
                                                                            <svg class="icon icon-primary icon-md">
                                                                                <use href="{{ $sprite }}#it-expand"></use>
                                                                            </svg>
                                                                        </span>
                                                                    </a>
                                                                    <ul class="collapse" id="{{ $collapseId }}" x-show="open" x-cloak>
                                                                        @foreach ($collapsedLinks as $link)
                                                                            <li>
                                                                                <a class="list-item px-0" href="{{ $link['url'] ?? '#' }}"><span>{{ $link['label'] ?? '' }}</span></a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <span class="hr-shadow-sm d-none d-lg-block"></span>
                    </div>
                @endif
            </div>

            @if ($contactsLinks !== [])
                <div class="col-12 col-lg-6 offset-lg-3 p-contacts">
                    <div class="cmp-contacts">
                        <div class="card w-100">
                            <div class="card-body">
                                <h2 class="title-medium-2-semi-bold">{{ $contactsTitle }}</h2>
                                <ul class="contact-list p-0">
                                    @foreach ($contactsLinks as $link)
                                        <li>
                                            <a class="list-item" href="{{ $link['url'] ?? '#' }}">
                                                <svg class="icon icon-primary icon-sm" aria-hidden="true">
                                                    <use href="{{ $sprite }}#{{ $link['icon'] ?? 'it-help-circle' }}"></use>
                                                </svg>
                                                <span>{{ $link['label'] ?? '' }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
