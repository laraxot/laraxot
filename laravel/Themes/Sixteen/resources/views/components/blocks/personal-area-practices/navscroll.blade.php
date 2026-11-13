{{--
    Indice laterale con barra di avanzamento (DCP-110): copia di report-partials/navscroll con un solo cambiamento, la barra di avanzamento ha
    un nome accessibile (`aria-label`): senza, axe segnala `aria-progressbar-name` (serious) in ogni pagina che usa il partial condiviso.
    Scroll-spy `dcNavscroll` montato per selettore `[data-bs-navscroll]`. Parametro: $nav = [suffix, title, items[anchor, label]].
    Usa $icon del chiamante.
--}}
<div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-{{ $nav['suffix'] ?? 'one' }}">
    <nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="{{ $nav['title'] ?? '' }}" data-bs-navscroll>
        <div class="navbar-custom" id="navbarNavProgress">
            <div class="menu-wrapper">
                <div class="link-list-wrapper">
                    <div class="accordion">
                        <div class="accordion-item">
                            <span class="accordion-header" id="accordion-title-{{ $nav['suffix'] ?? 'one' }}">
                                <button class="accordion-button pb-10 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $nav['suffix'] ?? 'one' }}" aria-expanded="true" aria-controls="collapse-{{ $nav['suffix'] ?? 'one' }}"> {{ $nav['title'] ?? '' }}
                                    <svg class="icon icon-xs right"><use href="{{ $icon('it-expand') }}"></use></svg></button>
                            </span>
                            <div class="progress">
                                <div class="progress-bar it-navscroll-progressbar" role="progressbar" aria-label="{{ $nav['title'] ?? '' }}" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div id="collapse-{{ $nav['suffix'] ?? 'one' }}" class="accordion-collapse collapse show" role="region" aria-labelledby="accordion-title-{{ $nav['suffix'] ?? 'one' }}">
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
