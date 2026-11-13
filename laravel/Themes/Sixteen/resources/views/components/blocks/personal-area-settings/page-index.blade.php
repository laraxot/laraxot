@props(['nav' => []])
{{--
    Indice laterale della pagina (DCP-111): stesso markup e stessi hook JS (`data-bs-navscroll`) di report-partials/navscroll, ma con la barra di
    avanzamento nascosta agli assistivi (e' solo decorativa: senza nome accessibile axe segnala `aria-progressbar-name`, serio).
    Il partial condiviso non si tocca: serve il blocco report-area, misurato a pixel e sull'HTML del riferimento. Parametro: $nav = [suffix, title, items[anchor, label]].
--}}
@php($suffix = $nav['suffix'] ?? 'index')
<div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-{{ $suffix }}">
    <nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="{{ $nav['title'] ?? '' }}" data-bs-navscroll>
        <div class="navbar-custom" id="navbarNavProgress">
            <div class="menu-wrapper">
                <div class="link-list-wrapper">
                    <div class="accordion">
                        <div class="accordion-item">
                            <span class="accordion-header" id="accordion-title-{{ $suffix }}">
                                <button class="accordion-button pb-10 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $suffix }}" aria-expanded="true" aria-controls="collapse-{{ $suffix }}"> {{ $nav['title'] ?? '' }}
                                    <svg class="icon icon-xs right" aria-hidden="true"><use href="{{ $icon('it-expand') }}"></use></svg></button>
                            </span>
                            <div class="progress" aria-hidden="true">
                                <div class="progress-bar it-navscroll-progressbar" role="progressbar" aria-label="{{ __('pub_theme::a11y.reading_progress') }}" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div id="collapse-{{ $suffix }}" class="accordion-collapse collapse show" role="region" aria-labelledby="accordion-title-{{ $suffix }}">
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
