{{--
    Accordion FAQ `cmp-accordion faq` (collapse montato dal JS condiviso da data-bs-toggle/data-bs-target) e pulsante "Carica altre domande".
    $section = {accordion_id, col_class, items[{id, question, answer}] | source, visible?, filterable?, empty?, more{col_class, label}}. Il riferimento ha
    aria-expanded="true" su pulsanti chiusi: qui NO (WCAG 4.1.2). Il pannello parte chiuso, quindi il pulsante dichiara `false`; il JS condiviso lo
    alterna al clic. Con `true` iniziale lo stato era invertito per chi usa un lettore di schermo (audit 2026-11-11, F-01); guardia: CollapsedButtonsStartCollapsedTest.
    `source: "faq"` legge le domande pubblicate da Fixcity (ListPublishedFaqsAction) al posto di `items`; l'id mancante diventa `faq-<n>`.
    Senza `visible` e `filterable` il markup e' quello del riferimento (statico). Con `visible` il pulsante `more` mostra le altre domande; con
    `filterable` l'elenco segue il campo di ricerca che ha `filter` = accordion_id (evento `list-filter`); `empty` e' il testo senza risultati. Alpine, nessun CSS.
    ponytail: la sorgente `faq` e' cablata qui; se arriva una seconda sorgente, mappa `source` -> Action in config.
--}}
@php
    $items = $section['items'] ?? (($section['source'] ?? null) === 'faq' ? app(\Modules\Fixcity\Actions\Faq\ListPublishedFaqsAction::class)->execute() : []);
    $accordionId = $section['accordion_id'] ?? '';
    $visible = (int) ($section['visible'] ?? 0);
    $live = $visible > 0 || ($section['filterable'] ?? false);
@endphp
<div class="row"@if ($live) x-data="{ q: '', shown: {{ $visible > 0 ? $visible : count($items) }}, total: {{ count($items) }}, hits: {{ count($items) }}, filter(q) { this.q = q.trim().toLowerCase(); this.hits = [...$root.querySelectorAll('[data-text]')].filter((el) => el.dataset.text.includes(this.q)).length; } }" x-on:list-filter.window="$event.detail.target === '{{ $accordionId }}' && filter($event.detail.q)"@endif>
    <div class="{{ $section['col_class'] ?? 'col-12' }}">
        <div class="cmp-accordion faq">
            <div class="accordion" id="{{ $accordionId }}">
                @foreach ($items as $item)
                    @php($itemId = $item['id'] ?? 'faq-'.$loop->iteration)
                    <div class="accordion-item"@if ($live) data-text="{{ \Illuminate\Support\Str::lower(($item['question'] ?? '').' '.($item['answer'] ?? '')) }}" x-show="q === '' ? {{ $loop->index }} < shown : $el.dataset.text.includes(q)"@endif>
                        <h2 class="accordion-header" id="heading{{ $itemId }}">
                            <button class="accordion-button collapsed title-snall-semi-bold py-3" style="color:#17324d !important;background-color:#fff !important" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $itemId }}" aria-expanded="false" aria-controls="collapse{{ $itemId }}">
                                <div class="button-wrapper">{{ $item['question'] ?? '' }}
                                    <div class="icon-wrapper">
                                        <svg class="icon icon-xs me-1 icon-primary"><use href="{{ $icon('it-expand') }}" xlink:href="{{ $icon('it-expand') }}"></use><span class=""></span></svg>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse{{ $itemId }}" class="accordion-collapse collapse p-0" data-bs-parent="#accordionExample{{ $itemId }}" role="region" aria-labelledby="heading{{ $itemId }}">
                            <div class="accordion-body"><p class="mb-3">{{ $item['answer'] ?? '' }}</p></div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($live && filled($section['empty'] ?? null))
                <p class="text-paragraph mt-3" x-show="hits === 0" x-cloak>{{ $section['empty'] }}</p>
            @endif
            @if ($live)
                {{-- Regione di stato SEMPRE nel DOM (una che compare con x-show non viene annunciata): vuota finche' non si digita, poi "Domande trovate: N" o il testo `empty`. --}}
                <div class="visually-hidden" role="status" x-text="q === '' ? '' : (hits === 0 ? {{ \Illuminate\Support\Js::from((string) ($section['empty'] ?? '')) }} : {{ \Illuminate\Support\Js::from(__('pub_theme::faq.search.results')) }}.replace(':count', hits))"></div>
            @endif
        </div>
    </div>
    @if (filled($section['more']['label'] ?? null))
        <div class="{{ $section['more']['col_class'] ?? 'col-12' }}"@if ($visible > 0) x-show="q === '' && shown < total" x-on:click="shown = total"@endif>
            <button type="button" class="btn btn-outline-primary w-100 mt-40 mb-40 title-medium-bold" style="color:#17324d !important;background-color:#fff !important"><span class="">{{ $section['more']['label'] }}</span></button>
        </div>
    @endif
</div>
