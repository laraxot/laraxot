@props(['nav' => []])
{{--
    Indice laterale con scroll-spy (`data-bs-navscroll`, controller `dcNavscroll` del tema): `nav` = aria_label, title,
    items[] (anchor, label). Stesso markup di event-detail.
--}}
@php
    $icon = static fn (string $name): string => \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp
<div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-one">
    <nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="{{ $nav['aria_label'] ?? '' }}" data-bs-navscroll>
        <div class="navbar-custom" id="navbarNavProgress">
            <div class="menu-wrapper">
                <div class="link-list-wrapper">
                    <div class="accordion">
                        <div class="accordion-item">
                            <span class="accordion-header" id="accordion-title-one">
                                <button class="accordion-button pb-10 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-one" aria-expanded="true" aria-controls="collapse-one"> {{ $nav['title'] ?? '' }} <svg class="icon icon-xs right"><use href="{{ $icon('it-expand') }}"></use></svg></button>
                            </span>
                            <div class="progress">
                                <div class="progress-bar it-navscroll-progressbar" role="progressbar" aria-label="{{ __('pub_theme::a11y.reading_progress') }}" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div id="collapse-one" class="accordion-collapse collapse show" role="region" aria-labelledby="accordion-title-one">
                                <div class="accordion-body">
                                    <ul class="link-list" data-element="page-index">
                                        @foreach ($nav['items'] ?? [] as $item)
                                            <li class="nav-item"><a class="nav-link" href="#{{ $item['anchor'] ?? '' }}"><span>{{ $item['label'] ?? '' }}</span></a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
