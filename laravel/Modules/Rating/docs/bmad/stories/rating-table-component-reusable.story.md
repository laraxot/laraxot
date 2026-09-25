# Story: rating-table-component-reusable
**Status**: ready-for-dev
**Modulo**: Rating
**Epic**: componente blade riutilizzabile per tabella rating (esportazioni + UI)

## AC
- [ ] Componente blade `components/rating-table.blade.php` riutilizzabile (modulo Rating)
- [ ] Accetta `$row` (modello con relazione `ratings`) e opzioni: `showNote`, `rowspan`, `alignValue`
- [ ] Colonne: txt (col1), value bold (col2), note rowspan-only (col4)
- [ ] Usa `{!! $rating->getTxtHtml() !!}`, `{!! $rating->getValueHtml() !!}`, `{!! $rating->getNoteHtml() !!}` (esistenti su BaseRating)
- [ ] Blade schema valido: `@php $note='' @endphp`, accumulo note nel loop, `rowspan="{{ $loop->count }}"` solo ultimo iterazione
- [ ] PHPStan Modules/Rating [OK]
- [ ] Usato in almeno un punto (esportazione XLS o vista scheda)

## Note
- Second brain: aggiungere regola `blade-rating-table-component.md` in `bashscripts/ai/wiki/memories/`
- QMD: aggiornare `qmd update` dopo creazione componente
