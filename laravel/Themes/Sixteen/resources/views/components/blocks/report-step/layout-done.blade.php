{{--
    Conferma (sito/segnalazione-04-conferma): titolo con icona di successo, righe di testo con parti in linea
    (JSON: heading.lines[class, parts[text | strong | link{label,url} | br]]), pulsante ricevuta (opzionale; con `heading.button.url` e' un collegamento), rimando all'area riservata (opzionale) e servizi correlati.
--}}
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @include($parts.'.breadcrumb')
            <div class="cmp-heading p-0">
                <div class="categoryicon-top d-flex">
                    <svg class="icon icon-success mr-10 icon-md" aria-hidden="true"><use href="{{ $icon($d('heading.icon', 'it-check-circle')) }}"></use></svg>
                    <h1 class="title-xxxlarge">{{ $d('title') }}</h1>
                </div>
                @foreach ($d('heading.lines', []) as $line)
                    <p class="{{ $line['class'] ?? 'subtitle-small' }}">
                        @foreach ($line['parts'] ?? [] as $part)
                            @if (isset($part['strong']))<strong> {{ $part['strong'] }}</strong>
                            @elseif (isset($part['link']))<a href="{{ $part['link']['url'] ?? '#' }}" class="t-primary"> {{ $part['link']['label'] ?? '' }}</a>
                            @elseif ($part['br'] ?? false)<br>
                            @else
                                {{ $part['text'] ?? '' }}
                            @endif
                        @endforeach
                    </p>
                @endforeach
                @if (filled($d('heading.button.label')))
                    {{-- `heading.button.url`: nelle pagine reali la ricevuta e' un collegamento (stesse classi); senza url resta il pulsante del riferimento --}}
                    <{!! filled($d('heading.button.url')) ? 'a href="'.e($d('heading.button.url')).'"' : 'button type="button"' !!} class="btn btn-outline-primary bg-white fw-bold">
                        <span class="rounded-icon"><svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $icon($d('heading.button.icon', 'it-download')) }}"></use></svg></span>
                        <span class="">{{ $d('heading.button.label') }}</span></{!! filled($d('heading.button.url')) ? 'a' : 'button' !!}>
                @endif
            </div>
            @if (filled($d('heading.followup.label')))
                <p class="mt-3">
                    <a href="{{ $d('heading.followup.url', '#') }}" class="t-primary text-paragraph">{{ $d('heading.followup.label') }}</a>
                    <span class="text-paragraph"> {{ $d('heading.followup.text') }}</span>
                </p>
            @endif
        </div>
    </div>
    {{-- senza servizi correlati (nessun dato dell'ente da mostrare) la sezione non c'e': niente titolo sopra una lista vuota --}}
    @if (! empty($d('related.items', [])))
    <hr class="d-none d-lg-block mt-40 mb-0">
    <div class="row justify-content-center mb-3 mb-md-5">
        <div class="col-12 col-lg-10">
            <div class="cmp-icon-list">
                <h2 class="title-xxlarge mt-40 mb-2 mb-lg-4 mb-lg-4" id="related-service">{{ $d('related.title') }}</h2>
                <div class="link-list-wrapper">
                    <ul class="link-list">
                        @foreach ($d('related.items', []) as $item)
                            <li class="shadow mb-4">
                                <a class="list-item icon-left t-primary title-small-semi-bold" href="{{ $item['url'] ?? '#' }}">
                                    <span class="list-item-title-icon-wrapper">
                                        <svg class="icon icon-sm align-self-start icon-color" aria-hidden="true"><use href="{{ $icon($item['icon'] ?? 'it-settings') }}"></use></svg>
                                        <span class="list-item-title title-small-semi-bold">{{ $item['label'] ?? '' }}</span>
                                    </span>
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
